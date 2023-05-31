<?php

namespace App\Tests\Integration\Controller;

use App\Entity\Book;
use App\Repository\UserRepository;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookInfoControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private $bookRepository;
    private UserRepository|null $userRepository;
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->userRepository = static::getContainer()->get(UserRepository::class);
        $this->bookRepository = static::getContainer()->get(BookRepository::class);
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
     * @depends testTerms
     */
    public function testLikeBookWithoutLogin():void
    {
        $this->client->followRedirects();
        $selectedBookISBN = 9780006479673;
        $selectedBook = $this->bookRepository->findByISBN($selectedBookISBN);
        $this->assertCount(1,$selectedBook);
        $id = $selectedBook[0]->getId();
        $this->client->request('GET', '/book_info/'.$id);
        $this->assertResponseIsSuccessful();
        $this->client->request('POST','/book_like',[
            'book_id' => $id,
            'user_id' => 1,
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(),'Fail for requesting book_like url');
        $path = parse_url($this->client->getRequest()->getUri(), PHP_URL_PATH);
        $direct = substr($path, 1);
        $this->assertEquals('login',$direct,'The user should be redirected to login page.');
    }

    /**
     *@depends testTerms
     */
    public function testBookLike(): void
    {
        //displayed book ISBN
        $selectedBookISBN = 9780006479673;
        $selectedBook = $this->bookRepository->findByISBN($selectedBookISBN);
        $this->assertCount(1,$selectedBook,'Only one book can be found by ISBN');
        //liked book ISBN
        $expectedLikedBookISBN = 9780002261982;
        $expectedLikedBook = $this->bookRepository->findByISBN($expectedLikedBookISBN);
        $this->assertCount(1,$expectedLikedBook,'Only one book can be found by ISBN');
        //get book info page
        $id = $selectedBook[0]->getId();
        $this->client->request('GET', '/book_info/'.$id);
        $this->assertResponseIsSuccessful('Fail direct to book_info page');
        //get user information
        $user = $this->userRepository->findOneBy(['username'=>'test_user']);
        $this->client->loginUser($user);
        $likedBook = $user->getBooks();
        $this->assertCount(1,$likedBook,'Only one book is in liked books');
        $this->assertEquals($expectedLikedBook[0],$likedBook[0],'The book should be the book with ISBN 9780002261982');
        //test add favorite book function
        $this->client->request('POST','/book_like',[
            'book_id' => $id,
            'user_id' => $user->getId(),
        ]);
        $this->assertResponseIsSuccessful('Fail for requesting book_like url');
        $user = $this->userRepository->findOneBy(['username'=>'test_user']);
        $likedBook = $user->getBooks();
        $this->assertCount(2,$likedBook,'There should be 2 liked books');
        $this->assertEquals($expectedLikedBookISBN,$likedBook[0]->getISBN13(),
            'The book with 9780002261982 should be in the list.');
        $this->assertEquals($selectedBookISBN,$likedBook[1]->getISBN13(),
            'The book with 9780006479673 should be in the list.');
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
        $this->client->loginUser($user);
        $likedBook = $user->getBooks();
        $this->assertCount(1,$likedBook);
        $this->assertTrue($likedBook->contains($selectedBook[0]));
        $this->client->request('POST','/book_like',[
            'book_id' => $id,
            'user_id' => $user->getId(),
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
        $user = $this->userRepository->findOneBy(['username'=>'test_user']);
        $likedBooks = $user->getBooks();
        $this->assertCount(0,$likedBooks,
            'The book with 9780002261982 should not appear in the liked list');
    }

    public function testInvalidBooks():void
    {
        $id = 9780002261982;
        $this->client->request('GET', '/book_info/'.$id);
        $this->assertEquals(404,$this->client->getResponse()->getStatusCode());
        $this->assertSelectorExists('h1','The book does not exist');
    }
}
