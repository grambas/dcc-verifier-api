<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Mailer;
use App\Service\OAuthService;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RequestCredentialsController extends AbstractController
{
    protected $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @Route("/api_credentials", name="request_credentials", methods={"GET"})
     *
     * @OA\Get(
     *     summary="Open this link in browser in order to get api credentials",
     * )
     */
    public function index(): Response
    {
        return $this->render('request_credentials.html.twig');
    }

    /**
     * @Route("/post_api_credentials", name="post_api_credentials", methods={"POST"})
     */
    public function post(Request $request, OAuthService $authService): RedirectResponse
    {
        $email = $request->request->get('email');

        $apiUser = $authService->getExistingApiUser($email);
        if ($apiUser) {
            $this->mailer->sendCredentialsRemind($email, $apiUser->getOAuthClient());
        } else {
            $client = $authService->createClient($email);
            $this->mailer->sendCredentials($email, $client);
        }

        return $this->redirectToRoute('app.swagger_ui');
    }
}
