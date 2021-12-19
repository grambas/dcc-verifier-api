<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\ApiException;
use App\Request\Validate as ValidateRequest;
use App\Response\Error;
use App\Response\Validate as ValidateResponse;
use App\Service\ValidationHelper;
use Exception;
use Grambas\DateValidator;
use Grambas\DccVerifier;
use Grambas\Repository\GermanyTrustListRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ValidateController extends AbstractController
{
    protected $resourcesDir;
    protected $serializer;
    protected $logger;

    public function __construct(
        SerializerInterface $serializer,
        string $resourcesDir,
        LoggerInterface $logger
    ) {
        $this->serializer = $serializer;
        $this->resourcesDir = $resourcesDir;
        $this->logger = $logger;
    }

    /**
     * @Route("/api/dcc/verify",
     *     methods={"POST"},
     *     defaults={"oauth2_scopes" = {"verify"}}
     *)
     * @OA\Post(
     *     summary="verify digital covid-19 certificate",
     * )
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns if certificate is valid by given ruleset and certificate validation date range",
     *     @OA\JsonContent(ref = @Model(type = ValidateResponse::class))
     * )
     *
     * @OA\RequestBody(
     *    description="Pet to add to the store",
     *    required=true,
     *    @OA\JsonContent(ref = @Model(type = ValidateRequest::class))
     * )
     *
     * @OA\Tag(name="Digital COVID-19 certificate")
     *
     * @Security(name="client_credentials")
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            /** @var ValidateRequest $validateRequest */
            $validateRequest = $this->serializer->deserialize(
                $request->getContent(),
                ValidateRequest::class,
                'json'
            );
        } catch (Exception $exception) {
            throw new ApiException(400, [new Error(-1, 'Invalid request payload.')], $exception);
        }

        $trustListRepository = new GermanyTrustListRepository($this->resourcesDir.'/dsc');
        $verifier = new DccVerifier($validateRequest->certificate, $trustListRepository);

        try {
            $dcc = $verifier->decode();
        } catch (Exception $exception) {
            throw new ApiException(400, [new Error(-2, 'QR Code could not be decoded')], $exception);
        }

        $response = new ValidateResponse();

        if (!$dcc->isValidFor(ValidationHelper::getBinaryRepresentation($validateRequest->documentTypes))) {
            $response->addError(100, 'Document is not valid for given types');
        }

        if (!ValidationHelper::isSamePerson($validateRequest, $dcc)) {
            $response->addError(101, 'It seems that certificate belongs not for requested person');
        }

        try {
            $verifier->verify();
        } catch (Exception $exception) {
            $this->logger->error('Exception by verifying open ssl signature: ' . $exception->getMessage(), [
                'exception' => $exception
            ]);
            $response->addError(102, 'certificate signature could not be verified');
        }

        try {
            $dateValidator = new DateValidator($dcc);

            $response->setValidFrom($dateValidator->getValidFrom());
            $response->setValidTo($dateValidator->getValidTo());
        } catch (Exception $exception) {
            //todo log?
            $response->addError(103, 'Validation date parse error');
        }

        $payload = $this->serializer->serialize($response, 'json');

        return new JsonResponse($payload, 200, [], true);
    }
}
