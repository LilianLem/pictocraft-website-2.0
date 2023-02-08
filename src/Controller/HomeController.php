<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route("/", name: "home")]
    public function home(): Response
    {
        return $this->getUser()
            ? $this->render("home/custom.html.twig")
            : $this->render("home/guest.html.twig");
    }
}
