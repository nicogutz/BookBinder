<?php

namespace App\Tests\Integration\Controller;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SearchControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private $entityManager;
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }
    public function testSearch(): void
    {
        $this->client->request('GET', '/search');
        $this->assertResponseIsSuccessful();
    }

    public function testSearchISBN(): void
    {
        $isbn = 9780002261982;
        $this->client->request('GET', '/search_isbn/'.$isbn);
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $content = $response->getContent();
        $this->assertJson($content);
        $result = json_decode($content, true);
        $bookRepository = $this->entityManager->getRepository(Book::class);
        $expect = $bookRepository->findByISBN($isbn);
        foreach ($expect as $index => $expect)
        {
            $this->assertSame($expect->getId(),$result[$index]['id']);
            $this->assertSame($expect->getTitle(),$result[$index]['title']);
            $this->assertSame($expect->getAuthor(),$result[$index]['author']);
            $this->assertSame($expect->getSubTitle(),$result[$index]['subtitle']);
            $this->assertSame($expect->getThumbnail(),$result[$index]['thumbnail']);
            $this->assertSame($expect->getYear(),$result[$index]['year']);
            $this->assertSame($expect->getPageNumber(),$result[$index]['pageNumber']);
            $this->assertSame($expect->getAverageRating(),$result[$index]['averageRating']);
            $this->assertSame($expect->getPrice(),$result[$index]['price']);
        }
    }

    public function testSearchTitle(): void
    {
        //test Equal research
        $title = "Spiders Web";
        $this->client->request('GET', '/search_title/'.$title);
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $content = $response->getContent();
        $this->assertJson($content);
        $result = json_decode($content, true);
        $bookRepository = $this->entityManager->getRepository(Book::class);
        $expect = $bookRepository->findByTitle($title);
        foreach ($expect as $index => $expect)
        {
            $this->assertSame($expect->getId(),$result[$index]['id']);
            $this->assertSame($expect->getTitle(),$result[$index]['title']);
            $this->assertSame($expect->getAuthor(),$result[$index]['author']);
            $this->assertSame($expect->getSubTitle(),$result[$index]['subtitle']);
            $this->assertSame($expect->getThumbnail(),$result[$index]['thumbnail']);
            $this->assertSame($expect->getYear(),$result[$index]['year']);
            $this->assertSame($expect->getPageNumber(),$result[$index]['pageNumber']);
            $this->assertSame($expect->getAverageRating(),$result[$index]['averageRating']);
            $this->assertSame($expect->getPrice(),$result[$index]['price']);
        }
    }

    public function testSearchAuthor(): void
    {
        //test Equal Research
        $author = "Charles Osborne";
        $this->client->request('GET', '/search_author/'.$author);
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $content = $response->getContent();
        $this->assertJson($content);
        $result = json_decode($content, true);
        $bookRepository = $this->entityManager->getRepository(Book::class);
        $expect = $bookRepository->findByAuthor($author);
        foreach ($expect as $index => $expect)
        {
            $this->assertSame($expect->getId(),$result[$index]['id']);
            $this->assertSame($expect->getTitle(),$result[$index]['title']);
            $this->assertSame($expect->getAuthor(),$result[$index]['author']);
            $this->assertSame($expect->getSubTitle(),$result[$index]['subtitle']);
            $this->assertSame($expect->getThumbnail(),$result[$index]['thumbnail']);
            $this->assertSame($expect->getYear(),$result[$index]['year']);
            $this->assertSame($expect->getPageNumber(),$result[$index]['pageNumber']);
            $this->assertSame($expect->getAverageRating(),$result[$index]['averageRating']);
            $this->assertSame($expect->getPrice(),$result[$index]['price']);
        }
    }
}
