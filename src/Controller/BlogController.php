<?php

namespace App\Controller;

use App\Form\ArticleType;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class BlogController extends AbstractController
{
    //Affichage de la page index
    /**
     * @Route("/", name="index")
     */
    public function index(ManagerRegistry $doctrine) 
    {
        $articles = $doctrine->getRepository(Article::class)->findAll(
            //['isPublished' => true],
            ['publicationDate' => 'desc']
        );

        return $this->render('index.html.twig', ['articles' => $articles]);
    }

    //Affichage de la page index
    /**
     * @Route("/show/{id}", name="show")
     */
    public function show($id, ManagerRegistry $doctrine) 
    {
        $article = $doctrine->getRepository(Article::class)->find($id);
        return $this->render('show.html.twig', ['article' => $article]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit(Article $article, Request $request, ManagerRegistry $doctrine)
    {
        $oldPicture = $article->getPicture();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setLastUpdateDate(new \DateTime());

            if ($article->getIsPublished()) {
                $article->setPublicationDate(new \DateTime());
            }

            if ($form['picture']->getData() !== null && $form['picture']->getData() !== $oldPicture) {
                $file = $form['picture']->getData();
                $fileName = uniqid() . '.' . $file->guessExtension();

                try {

                    $file->move(
                         $this->getParameter('images_directory'), //Le dossier dans lequel le fichier sera chargé
                         $fileName
                     );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                $article->setPicture($fileName);
            
            } else {
                $article->setPicture($oldPicture);
            }

            //Ajout de l'article modifié dans la base de données
            $em = $doctrine->getManager(); //on récupère l'Entity manager
            $em->persist($article);//On confie notre entité à l'entity manager (on persist l'entité)
            $em->flush(); //On exécute la requete

            // return new Response('L\'article a bien été modifié');
            return $this->redirect('/');
        }

        return $this->render('edit.html.twig',[
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    //Ajout d'un article via le formulaire dédié

    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request, ManagerRegistry $doctrine)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article->setLastUpdateDate(new \DateTime());

            //Chargement de l'image
            if ($form['picture']->getData() !== null) {
                $file = $form['picture']->getData(); //Récupération du fichier insérer dans le formulaire
                $fileName = uniqid() . '.' . $file->guessExtension();
                try {

                    $file->move(
                         $this->getParameter('images_directory'), //Le dossier dans lequel le fichier sera chargé
                         $fileName
                     );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                $article->setPicture($fileName);

            }

            if ($article->getIsPublished()) {
                $article->setPublicationDate(new \DateTime());
            }

            //Ajout de l'article dans la base de données
            $em = $doctrine->getManager(); //on récupère l'Entity manager
            $em->persist($article);//On confie notre entité à l'entity manager (on persist l'entité)
            $em->flush(); //On exécute la requete

            //return new Response('L\'article a bien été enregistré');
            return $this->redirect('/');
        }

        return $this->render('add.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/delete/{articleId}", name="delete", requirements={"articleId"="\d+"})
     */
    public function delete(EntityManagerInterface $entityManager, $articleId)
    {
        $article = $entityManager->getRepository(Article::class)->find($articleId);

        if(empty($article)) {
            $this->addFlash(
                type: "warning",
                message: "Impossible de supprimer l'article"
            );
        } else {
            $entityManager->remove($article);
            $entityManager->flush();

            $title = $article->getTitle();

            $this->addFlash(
                type: "warning",
                message: "L'article " . $title . "a été supprimée avec succès");
        };
        return $this->redirect('/');
    }
}