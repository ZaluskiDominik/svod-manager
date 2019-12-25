<?php

namespace App\Controller\API;

use App\Repository\VideoEntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VideoController extends AbstractController
{
    /** @var VideoEntityRepository */
    private $videoRepository;

    public function __construct(VideoEntityRepository $videoRepository)
    {
        $this->videoRepository = $videoRepository;
    }

    /** @Route("/api/videos", methods={"GET"}) */
    public function getVideos(Request $request)
    {
        if ($videoId = $request->query->get('videoId')) {
            $video = $this->videoRepository->find($videoId);
            if ($video) {
                return new JsonResponse([
                    'video' => $video
                ]);
            }

            return new Response('', 404);
        }

        return new JsonResponse([
            'videos' => $this->videoRepository->findAll()
        ]);
    }
}
