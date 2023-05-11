<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\String\u;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function search(): Response
    {
        return $this->render('search/search.html.twig', [
        ]);
    }
}