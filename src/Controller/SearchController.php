<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SearchController extends AbstractController
{
    private BookRepository $repository;

    /**
     * This method is the constructor of the class, it initializes the book repository used by most methods.
     */
    public function __construct(BookRepository $repository)
    {
        $this->repository = $repository;
    }

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
     * @return Response
     */
    #[Route('/search_isbn/{isbn}', name: 'app_search_isbn', methods: ['GET'])]
    public function searchISBN(int $isbn): Response
    {
        $books = $this->repository->findByISBN($isbn);

        return $this->json($books);
    }

    /**
     * This method is the API endpoint for searching by title.
     * @param string $title
     * @return Response
     */
    #[Route('/search_title/{title}', name: 'app_search_title', methods: ['GET'])]
    public function searchTitle(string $title): Response
    {
        $books = $this->repository->findByTitle($title);

        return $this->json($books);
    }

    /**
     * This method is the API endpoint for searching by author.
     * @param string $author
     * @return Response
     */
    #[Route('/search_author/{author}', name: 'app_search_author', methods: ['GET'])]
    public function searchAuthor(string $author): Response
    {
        $books = $this->repository->findByAuthor($author);

        return $this->json($books);
    }

}