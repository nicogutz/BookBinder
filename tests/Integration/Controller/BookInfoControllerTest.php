<?php

namespace App\Tests\Integration\Controller;

use App\Entity\Book;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookInfoControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private $entityManager;
    private $bookRepository;
    private $userRepository;
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->bookRepository = $this->entityManager->getRepository(Book::class);
        $this->userRepository = $this->entityManager->getRepository(User::class);
    }

    public function testTerms():void
    {
        $isbn = 9780002261982;
        $expect = $this->bookRepository->findByISBN($isbn);
        $this->assertCount(1,$expect);
        $id = $expect[0]->getId();
        $this->client->request('GET', '/book_info/'.$id);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h4.box-title',$expect[0]->getTitle());
        $this->assertSelectorExists('h5',$expect[0]->getSubTitle());
        $this->assertSelectorExists('ul li',$expect[0]->getYear());
        $this->assertSelectorExists('ul li',$expect[0]->getGenre());
        $this->assertSelectorExists('ul li',$expect[0]->getISBN13());
    }

    /**
     *@depends testTerms
     */
    public function testBookLike(): void
    {
        //displayed book ISBN
        $selectedBookISBN = 9780006479673;
        $selectedBook = $this->bookRepository->findByISBN($selectedBookISBN);
        $this->assertCount(1,$selectedBook);
        //liked book ISBN
        $expectedLikedBookISBN = 9780002261982;
        $expectedLikedBook = $this->bookRepository->findByISBN($expectedLikedBookISBN);
        $this->assertCount(1,$expectedLikedBook);
        //get book info page
        $id = $selectedBook[0]->getId();
        $this->client->request('GET', '/book_info/'.$id);
        $this->assertResponseIsSuccessful();
        //get user information
        $user = $this->userRepository->findOneBy(['username'=>'test_user']);
        $likedBook = $user->getBooks();
        $this->assertCount(1,$likedBook);
        $this->assertEquals($expectedLikedBook[0],$likedBook[0]);
        //test add favorite book function
        $this->client->request('POST','/book_like',[
            'book_id' => $id,
            'user_id' => $user->getId(),
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
        $this->entityManager->refresh($user);
        $likedBooks = $user->getBooks();
        $this->assertCount(2,$likedBooks);
        $this->assertTrue($likedBooks->contains($selectedBook[0]));
        $this->assertTrue($likedBooks->contains($expectedLikedBook[0]));
    }

    /**
     *@depends testBookLike
     */
    public function testBookLikeToggle():void
    {
        $selectedBookISBN = 9780002261982;
        $selectedBook = $this->bookRepository->findByISBN($selectedBookISBN);
        $id = $selectedBook[0]->getId();
        $user = $this->userRepository->findOneBy(['username'=>'test_user']);
        $likedBook = $user->getBooks();
        $this->assertCount(1,$likedBook);
        $this->assertTrue($likedBook->contains($selectedBook[0]));
        $this->client->request('POST','/book_like',[
            'book_id' => $id,
            'user_id' => $user->getId(),
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
        $this->entityManager->refresh($user);
        $likedBooks = $user->getBooks();
        $this->assertCount(0,$likedBooks);
    }

    public function testInvalidBooks():void
    {
        $id = 9780002261982;
        $this->client->request('GET', '/book_info/'.$id);
        $this->assertEquals(404,$this->client->getResponse()->getStatusCode());
        $this->assertSelectorExists('h1','The book does not exist');
    }

    /**
     * @depends testTerms
     */
    public function testInvalidUser():void
    {
        $isbn = 9780002261982;
        $expect = $this->bookRepository->findByISBN($isbn);
        $this->assertCount(1,$expect);
        $id = $expect[0]->getId();
        $this->client->request('GET', '/book_info/'.$id);
        $this->assertResponseIsSuccessful();
        $this->client->request('POST','/book_like',[
            'book_id' => $id,
        ]);
        $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
    }
}