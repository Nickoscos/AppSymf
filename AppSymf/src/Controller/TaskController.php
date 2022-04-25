<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\Listing;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("{listingId}/task", name="task_", requirements={"listingId"="\d+"})
 */
class TaskController extends AbstractController
{
    /**
     * @Route("/new", name="create")
     */
    public function create($listingId, Request $request, EntityManagerInterface $entityManager)
    {
        #Récupération de l'objet listing concerné
        $listing = $entityManager->getRepository(Listing::class)->find($listingId);

        #Création d'un nouveau Task
        $task = new Task();
        #Liaison de la task avec le listing
        $task->setListing($listing);
        #Création du formulaire
        $form = $this->createForm(TaskType::class, $task);
        #Liaison avec la base de donnée
        $form->handleRequest($request);
        #Saisie des données dans la base si les données sont valides
        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('listing_show', ['listingId' => $listingId]);
        }
        #appel du template pour l'affichage du formulaire de Task
        return $this->render('task.html.twig', ['form' => $form->createView()]);
    }
}