<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/hello")
 */

class HelloController extends AbstractController {

    /**
     * @Route("/{name}")
     */
    public function world($name) {
        return $this->render('hello.html.twig', ['name' => $name]);
    }
}