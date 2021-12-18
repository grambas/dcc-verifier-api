<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    public function __construct()
    {
    }

    /**
     * @Route("/", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        return $this->redirectToRoute('app.swagger_ui');
    }
}
