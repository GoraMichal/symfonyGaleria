<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ConferenceController extends AbstractController
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    #[Route('/', name: 'homepage')]
    public function index(ConferenceRepository $conferenceRepository): Response
    {
//        return new Response($twig->render('conference/index.html.twig', [
        return new Response($this->twig->render('conference/index.html.twig', [
            'all_conferences' => $conferenceRepository->findAll()
        ]));
    }

    #[Route('/conference/{id}', name: 'conference')]
    public function show(Request $request, Conference $conference, CommentRepository $commentRepository, ConferenceRepository $conferenceRepository): Response
    {
        $offset = max(0, $request->query->getInt('offset', 0));

        $paginator = $commentRepository->getCommentPaginator($conference, $offset);

        return new Response($this->twig->render('conference/show.html.twig', [
            'conference' => $conference,
            'conferences' => $conferenceRepository->findAll(),
            'all_comments' => $paginator,
            'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE)
        ]));
    }


//    public function show(Environment $twig, Conference $conference, CommentRepository $commentRepository): Response
//    {
//        return new Response($twig->render('conference/show.html.twig', [
//                'conference' => $conference,
//                'all_comments' => $commentRepository->findBy(['conference' => $conference], ['createdAt' => 'DESC'])
//            ]));
//    }

//    #[Route('/', name: 'homepage')]
//    #[Route("/hello/{name}", name: "homepage")]

//    public function index(string $name = 'Michal'): Response
//    {
//        return $this->json([
//            'message' => 'Welcome to your new controller!',
//            'path' => 'src/Controller/AdminController.php',
//        ]);
//    }

}