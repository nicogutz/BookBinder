<?php

namespace App\Tests\Integration\Repository;

use App\Entity\Book;
use App\Entity\User;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @author  Yuhao Ma
 */
class BookRepositoryTest extends KernelTestCase
{
    /**
     * @var BookRepository
     */
    private $entityManager;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * @covers \App\Repository\BookRepository::findByID
     * @return void
     */
    public function testFindById(): void
    {
        $bookRepository = $this->entityManager->getRepository(Book::class);
        /**
         * Exactly Search
         */
        $ID = 2;
        $result = $bookRepository->findByID($ID);
        $this->assertSame(2, $result->getId(),'the ID of the book Spiders Web should be 2');
    }

    /**
     * @covers  \App\Repository\BookRepository::findByISBN
     * @return  void
     */
    public function testFindByISBN(): void
    {
        $bookRepository = $this->entityManager->getRepository(Book::class);
        /**
         *  Exactly Search
         */
        $ISBN = 9780002261982;
        $result = $bookRepository->findByISBN($ISBN);
        foreach ($result as $index => $item) {
            $this->assertSame(2, $item->getId(),'the ID of the book Spiders Web should be 2');
        }
        /**
         *  Likely Search with single return
         */
        $ISBN = 97800022619;
        $result = $bookRepository->findByISBN($ISBN);
        foreach ($result as $index => $item) {
            $this->assertSame(2, $item->getId(),'the ID of the book Spiders Web should be 2');
        }

        /**
         *  Likely Search with multiple return
         */
        $ISBN = 978000;
        $result = $bookRepository->findByISBN($ISBN);
        $count = count($result);
        $this->assertEquals(56, $count,'There should be 56 books in the searched list');
        /**
         *  Invalid Search
         */
        $ISBN = 'ufghebref';
        $result = $bookRepository->findByISBN($ISBN);
        $this->assertCount(0,$result,'The returned list should be empty');
    }

    /**
     * @covers \App\Repository\BookRepository::findByTitle
     * @return void
     */
    public function testFindByTitle(): void
    {
        $bookRepository = $this->entityManager->getRepository(Book::class);
        /**
         * Exactly Search
         */
        $Title = "Spiders Web";
        $result = $bookRepository->findByTitle($Title);
        foreach ($result as $index => $item) {
            $this->assertSame(2, $item->getId(),'the ID of the book Spiders Web should be 2');
        }
        /**
         * Likely Search with single return
         */
        $Title = "Spiders W";
        $result = $bookRepository->findByTitle($Title);
        foreach ($result as $index => $item) {
            $this->assertSame(2, $item->getId(),'the ID of the book Spiders Web should be 2');
        }
        /**
         * Likely Search with multiple return
         */
        $Title = "S";
        $result = $bookRepository->findByTitle($Title);
        $count = count($result);
        $this->assertEquals(2525, $count,'There should be 2525 books in the searched list');
        /**
         *  Invalid Search
         */
        $ISBN = 'ufghebref';
        $result = $bookRepository->findByTitle($ISBN);
        $this->assertCount(0,$result,'The returned list should be empty');
    }

    /**
     * @covers \App\Repository\BookRepository::findByAuthor
     * @return void
     */
    public function testFindByAuthor(): void
    {
        $bookRepository = $this->entityManager->getRepository(Book::class);
        /**
         * Exactly Search
         */
        $Author = "Charles Osborne";
        $result = $bookRepository->findByAuthor($Author);
        foreach ($result as $index => $item) {
            $this->assertSame(2, $item->getId(),'the ID of the book Spiders Web should be 2');
        }

        /**
         * Likely Search with single return
         */
        $Author = "Charles Osbor";
        $result = $bookRepository->findByAuthor($Author);
        foreach ($result as $index => $item) {
            $this->assertSame(2, $item->getId(),'the ID of the book Spiders Web should be 2');
        }
        /**
         * Likely Search with multiple return
         */
        $Author = "Charles";
        $result = $bookRepository->findByAuthor($Author);
        $count = count($result);
        $this->assertEquals(45, $count,'There should be 45 books in the searched list');
        /**
         *  Invalid Search
         */
        $ISBN = 'ufghebref';
        $result = $bookRepository->findByAuthor($ISBN);
        $this->assertCount(0,$result,'The returned list should be empty');
    }
}
