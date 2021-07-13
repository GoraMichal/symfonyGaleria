<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Conference;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ConferenceController extends AbstractController
{
    private $twig;
    private $entityManager;

    public function __construct(Environment $twig, EntityManagerInterface $entityManager)
    {
        $this->twig = $twig;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'homepage')]
    public function index(ConferenceRepository $conferenceRepository): Response
    {
        return new Response($this->twig->render('conference/index.html.twig', [
            'all_conferences' => $conferenceRepository->findAll()
        ]));
    }

    #[Route('/conference/{slug}', name: 'conference')]
    public function show(Request $request, Conference $conference, CommentRepository $commentRepository, ConferenceRepository $conferenceRepository, string $photoDir): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $comment->setConference($conference);

            if($photo = $form['photo']->getData()){
                $filename = bin2hex(random_bytes(6).'.'.$photo->guessExtension());
                try {
                    $photo->move($photoDir, $filename);
                } catch (FileException $e) { }

                $comment->setPhotoFilename($filename);
            }

            $this->entityManager->persist($comment); //zapis
            $this->entityManager->flush(); //zapytanie

            return $this->redirectToRoute('conference', ['slug' => $conference->getSlug()]);
        }

        $offset = max(0, $request->query->getInt('offset', 0));

        $paginator = $commentRepository->getCommentPaginator($conference, $offset);

        return new Response($this->twig->render('conference/show.html.twig', [
            'conference' => $conference,
            'conferences' => $conferenceRepository->findAll(),
            'all_comments' => $paginator,
            'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE),
            'comment_form' => $form->createView()
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