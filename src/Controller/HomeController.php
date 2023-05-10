<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\String\u;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function homepage(): Response
    {
        $books = [
            [
                'id' => '1',
                'title' => 'On Bended Knee',
                'author' => 'Boyz II Men',
                'thumbnail' => 'http://books.google.com/books/content?id=XA5AZoZ4CkAC&printsec=frontcover&img=1&zoom=1&source=gbs_api',
            ],
            [
                'id' => '2',
                'title' => 'Gangsta\'s Paradise',
                'author' => 'Coolio',
                'thumbnail' => 'http://books.google.com/books/content?id=XA5AZoZ4CkAC&printsec=frontcover&img=1&zoom=1&source=gbs_api',
            ],
            [
                'id' => '3',
                'title' => 'Waterfalls',
                'author' => 'TLC',
                'thumbnail' => 'http://books.google.com/books/content?id=XA5AZoZ4CkAC&printsec=frontcover&img=1&zoom=1&source=gbs_api',
            ],
            [
                'id' => '4',
                'title' => 'Creep',
                'author' => 'Radiohead',
                'thumbnail' => 'http://books.google.com/books/content?id=XA5AZoZ4CkAC&printsec=frontcover&img=1&zoom=1&source=gbs_api',
            ],
            [
                'id' => '5',
                'title' => 'Kiss from a Rose',
                'author' => 'Seal',
                'thumbnail' => 'http://books.google.com/books/content?id=XA5AZoZ4CkAC&printsec=frontcover&img=1&zoom=1&source=gbs_api',
            ],
            [
                'id' => '6',
                'title' => 'Fantasy',
                'author' => 'Mariah Carey',
                'thumbnail' => 'http://books.google.com/books/content?id=XA5AZoZ4CkAC&printsec=frontcover&img=1&zoom=1&source=gbs_api',
            ],
        ];

        return $this->render('homepage.html.twig', [
            'books' => $books,
        ]);
    }

}