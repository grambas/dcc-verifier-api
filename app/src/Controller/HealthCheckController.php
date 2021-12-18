<?php

declare(strict_types=1);

namespace App\Controller;

use App\Response\HealthCheck;
use DateTime;
use DateTimeInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HealthCheckController extends AbstractController
{
    /**
     * @Route("/health_check", methods={"GET"})
     *
     *	@OA\Get(
     * 		summary="Basic endpoint which indicates health status",
     * 	)
     *
     * @OA\Response(
     *     response=200,
     *     description="return some basic info which indicates health status",
     *     @OA\JsonContent(ref = @Model(type = HealthCheck::class))
     * )
     */
    public function __invoke(Request $request, string $trustListFile, string $trustListLastCheckFile): JsonResponse
    {
        return $this->json(
            new HealthCheck(
            $this->getFileModification($trustListLastCheckFile),
            $this->getFileModification($trustListFile)
        )
        );
    }

    private function getFileModification(string $file): ?string
    {
        if (!file_exists($file)) {
            return null;
        }

        $date = (new DateTime())->setTimestamp(filemtime($file));

        return $date->format(DateTimeInterface::ISO8601);
    }
}
