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
        $book = [
            "id" => 2,
            "year" => 2000,
            "genre" => "action_adventure",
            "title" => "Spiders Web",
            "isbn13" => 9780002261982,
            "subtitle" => "A Novel",
            "thumbnail" => "http://books.google.com/books/content?id=gA5GPgAACAAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api",
            "description" => "A new Christie for Christmas  a fulllength novel adapted from her acclaimed play by Charles Osborne Following BLACK",
        ];

        return $this->render('book/book_info.html.twig', [
            'book' => $book,
        ]);
    }

}