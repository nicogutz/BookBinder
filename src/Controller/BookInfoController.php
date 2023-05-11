<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\String\u;

class BookInfoController extends AbstractController
{
    #[Route('/book_info', name: 'app_book_info')]
    public function terms(): Response
    {
        return $this->render('book/book_info.html.twig');
    }

}