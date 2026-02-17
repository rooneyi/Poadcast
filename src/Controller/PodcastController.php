<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Entity\Podcast;
use App\Form\PodcastType;
use App\Repository\PodcastRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

class PodcastController extends AbstractController
{
    #[Route('/podcasts', name: 'app_podcast_index')]
    public function index(PodcastRepository $podcastRepository): Response
    {
        return $this->render('podcast/index.html.twig', [
            'podcasts' => $podcastRepository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    #[Route('/podcast/new', name: 'app_podcast_new')]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $podcast = new Podcast();
        $form = $this->createForm(PodcastType::class, $podcast);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $audioFile = $form->get('audioFile')->getData();
            $coverImage = $form->get('coverImage')->getData();

            if ($audioFile) {
                $originalFilename = pathinfo($audioFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$audioFile->guessExtension();

                try {
                    $audioFile->move(
                        $this->getParameter('podcasts_audio_directory'),
                        $newFilename
                    );
                } catch (\Exception $e) {
                    // Fail gracefully
                }
                $podcast->setAudioFile($newFilename);
            }

            if ($coverImage) {
                $originalCoverFilename = pathinfo($coverImage->getClientOriginalName(), PATHINFO_FILENAME);
                $safeCoverFilename = $slugger->slug($originalCoverFilename);
                $newCoverFilename = $safeCoverFilename.'-'.uniqid().'.'.$coverImage->guessExtension();

                try {
                    $coverImage->move(
                        $this->getParameter('podcasts_images_directory'),
                        $newCoverFilename
                    );
                } catch (\Exception $e) {
                    // Fail gracefully
                }
                $podcast->setCoverImage($newCoverFilename);
            }

            $entityManager->persist($podcast);
            $entityManager->flush();

            return $this->redirectToRoute('app_podcast_index');
        }

        return $this->render('podcast/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/podcast/{id}', name: 'app_podcast_show')]
    public function show(Podcast $podcast): Response
    {
        return $this->render('podcast/show.html.twig', [
            'podcast' => $podcast,
        ]);
    }
}
