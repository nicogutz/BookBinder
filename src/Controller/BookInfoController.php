<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use function Symfony\Component\String\u;

class BookInfoController extends AbstractController
{

    /**
     * This method is used to display the book information page. It
     * takes the book id as a parameter and uses the BookRepository to find the corresponding book.
     * @param int $id
     * @param BookRepository $repository
     * @param Security $security
     * @return Response
     */
    #[Route('/book_info/{id}', name: 'app_book_info')]
    public function terms(int $id, BookRepository $repository, Security $security): Response
    {
        $user = $security->getUser();

        try {
            $book = $repository->findByID($id);
        } catch (NonUniqueResultException) {
            $book = null;
        }

        if ($book === null) {
            throw $this->createNotFoundException('The book does not exist');
        }

        $book->setGenre(ucwords(str_replace('_', ' - ', $book->getGenre())));
        if ($user){
            $likes_book = $user->getBooks()->contains($book);
        } else{
            $likes_book = false;
        }

        return $this->render('book/book_info.html.twig', [
            'book' => $book,
            'likes_book' => $likes_book,
        ]);
    }

    /**
     * This method is used to add or remove a book from the user's favorites.
     * It is meant to be used from the book information page using a POST request.
     * @param Request $request
     * @param BookRepository $bookRepository
     * @param UserRepository $userRepository
     * @return Response
     */
    #[Route('/book_like', name: 'app_book_like', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function book_like_toggle(
        Request $request,
        BookRepository $bookRepository,
        UserRepository $userRepository
    ): Response {
        //Get the book and user id from the request.

        $book_id = $request->request->get('book_id');
        $user_id = $request->request->get('user_id');

        try {
            $book = $bookRepository->findByID($book_id);
            $user = $userRepository->findByID($user_id);
            if ($user->getBooks()->contains($book)) {
                $user->removeBook($book);
            } else {
                $user->addBook($book);
            }
            $userRepository->save($user, true);

            return $this->json([
                'success' => true,
            ]);
        } catch (NonUniqueResultException | Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage(),
            ]);

        }
    }

}