<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ConferenceController extends AbstractController
{
//    #[Route('/', name: 'homepage')]
    #[Route("/hello/{name}", name: "homepage")]

    public function index(string $name = 'Michal'): Response
    {
//        return $this->json([
//            'message' => 'Welcome to your new controller!',
//            'path' => 'src/Controller/AdminController.php',
//        ]);

        $greet = '';
        if($name){
            $greet = sprintf('<h1>Cześć %s!</h1>', htmlspecialchars($name));
        }

        return new Response(<<<EOF
        <html>
         <body>
            <p>Otwarcie wkrótce</p>
            $greet
         </body>
        </html>
        EOF
        );

    }
}