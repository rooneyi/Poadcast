<?php

namespace App\Controller;

use App\Repository\PodcastRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home_index')]
    public function index(PodcastRepository $podcastRepository): Response
    {
        return $this->render('index.html.twig', [
            'latest_podcasts' => $podcastRepository->findBy([], ['createdAt' => 'DESC'], 4),
        ]);
    }

}