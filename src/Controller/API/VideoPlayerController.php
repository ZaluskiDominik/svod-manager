<?php

namespace App\Controller\API;

use App\Repository\VideoPlayerEntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class VideoPlayerController extends AbstractController
{
    /** @var VideoPlayerEntityRepository */
    private $videoPlayerRepository;

    public function __construct(VideoPlayerEntityRepository $videoPlayerRepository)
    {
        $this->videoPlayerRepository = $videoPlayerRepository;
    }

    /** @Route("/api/video/players", methods={"GET"}) */
    public function getVideoPlayersAction()
    {
        return new JsonResponse([
            'players' => $this->videoPlayerRepository->findAll()
        ]);
    }
}
