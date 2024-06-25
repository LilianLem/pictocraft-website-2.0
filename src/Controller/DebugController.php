<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DebugController extends AbstractController
{
    #[Route("/debug/phpinfo-QbUWH36D4qEcDM", name: "debug_phpinfo")]
    public function phpinfo(): Response
    {
        return new Response("<html lang='fr'><body>".phpinfo()."</body>");
    }
}
