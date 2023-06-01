<?php

namespace App\Tests\Integration\Controller;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SearchControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private $bookRepository;
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $kernel = self::bootKernel();
        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->bookRepository = $entityManager->getRepository(Book::class);
    }
    public function testSearch(): void
    {
        $this->client->request('GET', '/search');
        $this->assertResponseIsSuccessful('Cannot get access to search page');
    }

    public function testSearchISBN(): void
    {
        $isbn = 9780002261982;
        $this->client->request('GET', '/search_isbn/'.$isbn);
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode(),'Cannot access to searchByISBN url');
        $content = $response->getContent();
        $this->assertJson($content);
        $result = json_decode($content, true);
        $expect = $this->bookRepository->findByISBN($isbn);
        foreach ($expect as $index => $expect)
        {
            $this->assertSame($expect->getId(),$result[$index]['id'],'id is wrong');
            $this->assertSame($expect->getTitle(),$result[$index]['title'],'title is wrong');
            $this->assertSame($expect->getAuthor(),$result[$index]['author'],'author is wrong');
            $this->assertSame($expect->getSubTitle(),$result[$index]['subtitle'],'subtitle is wrong');
            $this->assertSame($expect->getThumbnail(),$result[$index]['thumbnail'],'thumbnail is wrong');
            $this->assertSame($expect->getYear(),$result[$index]['year'],'year is wrong');
            $this->assertSame($expect->getPageNumber(),$result[$index]['pageNumber'],'pageNumber is wrong');
            $this->assertSame($expect->getAverageRating(),$result[$index]['averageRating'],'averageRating is wrong');
            $this->assertSame($expect->getPrice(),$result[$index]['price'],'price is wrong');
        }
    }

    public function testSearchTitle(): void
    {
        //test Equal research
        $title = "Spiders Web";
        $this->client->request('GET', '/search_title/'.$title);
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode(),'Cannot access to searchByTitle url');
        $content = $response->getContent();
        $this->assertJson($content);
        $result = json_decode($content, true);
        $expect = $this->bookRepository->findByTitle($title);
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
        $this->assertSame(200, $response->getStatusCode(),'Cannot access to searchByAuthor url');
        $content = $response->getContent();
        $this->assertJson($content);
        $result = json_decode($content, true);
        $expect = $this->bookRepository->findByAuthor($author);
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
