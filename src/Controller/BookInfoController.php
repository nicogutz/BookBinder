<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\String\u;

class BookInfoController extends AbstractController
{
    /**
     * @throws NonUniqueResultException
     */
    #[Route('/book_info/{id}', name: 'app_book_info')]
    public function terms(int $id, BookRepository $repository): Response
    {
        $book = $repository->findByID($id);

        if ($book === null) {
            throw $this->createNotFoundException('The book does not exist');
        }

        return $this->render('book/book_info.html.twig', [
            'book' => $book,
        ]);
    }

}