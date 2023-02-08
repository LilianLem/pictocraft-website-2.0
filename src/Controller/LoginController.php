<?php

namespace App\Controller;

use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route("/login", name: "app_login")]
    public function index(AuthenticationUtils $authenticationUtils, FormFactoryInterface $formFactory): Response
    {
        $form = $formFactory->createNamed("", LoginType::class, [
            "username" => $authenticationUtils->getLastUsername()
        ]);

        return $this->render("login/index.html.twig", [
            "formView" => $form->createView(),
            "error" => $authenticationUtils->getLastAuthenticationError()
        ]);
    }
}
