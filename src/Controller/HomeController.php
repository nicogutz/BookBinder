<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function homepage(Security $security): Response
    {
        $user = $security->getUser();
        if ($user) {
            $books = $user->getBooks();
        } else {
            $books = null;
        }
        return $this->render('homepage.html.twig', [
            'books' => $books
        ]);
    }

}