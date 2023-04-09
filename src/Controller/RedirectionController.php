<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RedirectionController extends AbstractController
{
    #[Route('/', name: 'redirection_/_to_quote')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_category_index');
    }
}
