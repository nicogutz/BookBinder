<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SearchController extends AbstractController
{
    /**
     * This method returns the search page.
     * @return Response
     */
    #[Route('/search', name: 'app_search')]
    public function search(): Response
    {
        return $this->render('search/search.html.twig', [
        ]);
    }

    /**
     * This method is the API endpoint for searching by ISBN.
     * @param int $isbn
     * @param BookRepository $repository
     * @return Response
     */
    #[Route('/search_isbn/{isbn}', name: 'app_search_isbn', methods: ['GET'])]
    public function searchISBN(int $isbn, BookRepository $repository): Response
    {
        $books = $repository->findByISBN($isbn);

        return $this->json($books);
    }

    /**
     * This method is the API endpoint for searching by title.
     * @param string $title
     * @param BookRepository $repository
     * @return Response
     */
    #[Route('/search_title/{title}', name: 'app_search_title', methods: ['GET'])]
    public function searchTitle(string $title, BookRepository $repository): Response
    {
        $books = $repository->findByTitle($title);
        return $this->json($books);
    }

    /**
     * This method is the API endpoint for searching by author.
     * @param string $author
     * @param BookRepository $repository
     * @return Response
     */
    #[Route('/search_author/{author}', name: 'app_search_author', methods: ['GET'])]
    public function searchAuthor(string $author, BookRepository $repository): Response
    {
        $books = $repository->findByAuthor($author);
        return $this->json($books);
    }

}