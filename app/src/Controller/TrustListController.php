<?php

declare(strict_types=1);

namespace App\Controller;

use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class TrustListController extends AbstractController
{
    protected $resourcesDir;
    protected $serializer;

    public function __construct(SerializerInterface $serializer, string $resourcesDir)
    {
        $this->serializer = $serializer;
        $this->resourcesDir = $resourcesDir;
    }

    /**
     * @Route("/api/dsc", methods={"GET"}, defaults={"oauth2_scopes" = {"verify"}})
     *
     * @OA\Get(
     *     summary="get current trust list",
     * )
     *   @OA\Response(
     *     response=200,
     *     description="trust list json file",
     *     @OA\JsonContent()
     *   )
     *
     * @OA\Response(
     *     response=404,
     *     description="trust list file does not exist",
     * )
     *
     * @OA\Tag(name="Trust List")
     *
     * @Security(name="client_credentials")
     */
    public function __invoke(Request $request, string $trustListFile): JsonResponse
    {
        if (!file_exists($trustListFile)) {
            return new JsonResponse(null, 404, []);
        }

        $content = file_get_contents($trustListFile);

        return new JsonResponse($content, 200, [], true);
    }
}
