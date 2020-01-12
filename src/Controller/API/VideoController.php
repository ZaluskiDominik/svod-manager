<?php

namespace App\Controller\API;

use App\Entity\VideoEntity;
use App\Entity\VideoPlayerEntity;
use App\Repository\VideoEntityRepository;
use App\Service\JsonRequestParserService;
use App\Service\SessionUserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VideoController extends AbstractController
{
    /** @var VideoEntityRepository */
    private $videoRepository;

    /** @var SessionUserService */
    private $sessionUserService;

    /** @var JsonRequestParserService */
    private $jsonRequestParserService;

    /** @var EntityManagerInterface */
    private $em;

    public function __construct(
        VideoEntityRepository $videoRepository,
        SessionUserService $sessionUserService,
        JsonRequestParserService $jsonRequestParserService,
        EntityManagerInterface $em
    ) {
        $this->videoRepository = $videoRepository;
        $this->sessionUserService = $sessionUserService;
        $this->jsonRequestParserService = $jsonRequestParserService;
        $this->em = $em;
    }

    /** @Route("/api/videos", methods={"GET"}) */
    public function getVideosAction(Request $request)
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

    /** @Route("/api/videos", methods={"POST"}) */
    public function createVideoAction(Request $request)
    {
        if (!$this->sessionUserService->hasSessionPublisher()) {
            return new Response('', 401);
        }
        $data = $this->jsonRequestParserService->parse($request);

        $publisher = $this->sessionUserService->getUser()->getUser();
        if (count($this->videoRepository->findBy([
            'title' => $data['title'],
            'publisher' => $publisher
        ]))) {
            return new JsonResponse([
                'message' => 'You already have video with that title'
            ], 409);
        }

        $videoPlayer = $this->em->getRepository(VideoPlayerEntity::class)->findBy([
            'name' => $data['videoPlayer']
        ])[0];
        $video = VideoEntity::fromArray($data);
        $video->setVideoPlayer($videoPlayer);
        $video->setPublisher($publisher);
        $this->em->persist($video);
        $this->em->flush();

        return new JsonResponse([
            'video' => $video
        ], 201);
    }

    /** @Route("/api/videos", methods={"PUT"}) */
    public function editVideoAction(Request $request)
    {
        if (!$this->sessionUserService->hasSessionPublisher()) {
            return new Response('', 401);
        }
        $data = $this->jsonRequestParserService->parse($request);

        $publisher = $this->sessionUserService->getUser()->getUser();
        $video = $this->videoRepository->find($data['id']);
        if (!$video) {
            return new Response('', 404);
        }

        if ($video->getTitle() !== $data['title'] && count($this->videoRepository->findBy([
            'title' => $data['title'],
            'publisher' => $publisher
        ]))) {
            return new JsonResponse([
                'message' => 'You already have video with that title'
            ], 409);
        }

        $videoPlayer = $this->em->getRepository(VideoPlayerEntity::class)->findBy([
            'name' => $data['videoPlayer']
        ])[0];
        $video = VideoEntity::fromArray($data);
        $video->setVideoPlayer($videoPlayer);
        $video->setPublisher($publisher);
        $this->em->merge($video);
        $this->em->flush();

        return new JsonResponse([
            'video' => $video
        ], 200);
    }

    /** @Route("/api/videos", methods={"DELETE"}) */
    public function deleteVideoAction(Request $request)
    {
        if (!$this->sessionUserService->hasSessionPublisher()) {
            return new Response('', 401);
        }
        $data = $this->jsonRequestParserService->parse($request);

        $video = $this->videoRepository->find($data['id']);
        if (!$video) {
            return new Response('', 404);
        }

        $this->em->remove($video);
        $this->em->flush();

        return new Response('', 200);
    }
}
