<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function search(): Response
    {
        return $this->render('search/search.html.twig', [
        ]);
    }

    #[Route('/search_isbn/{isbn}', name: 'app_search_isbn', methods: ['GET'])]
    public function searchISBN(int $isbn, BookRepository $repository): Response
    {
        $books = $repository->findByISBN($isbn);

        return $this->json($books);
    }
    #[Route('/search_title/{title}', name: 'app_search_title', methods: ['GET'])]
    public function searchTitle(string $title, BookRepository $repository): Response
    {
        $books = $repository->findByTitle($title);
        return $this->json($books);
    }

    #[Route('/search_author/{author}', name: 'app_search_author', methods: ['GET'])]
    public function searchAuthor(string $author, BookRepository $repository): Response
    {
        $books = $repository->findByAuthor($author);
        return $this->json($books);
    }

}