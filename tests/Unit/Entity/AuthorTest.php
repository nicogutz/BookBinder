<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Author;
use App\Entity\Book;
use PHPUnit\Framework\TestCase;

class AuthorTest extends TestCase
{
    public function testAuthorCreate():void
    {
        $author = new Author();
        $author ->setName('test_author');
        $this->assertEquals('test_author',$author->getName(),'Author name should be test_author');
    }

    public function testAddBook():void
    {
        $book1 = new Book();
        $this->assertNotNull($book1);
        $author = new Author();
        $author->addBook($book1);
        $author->addBook($book1);
        $this->assertCount(1,$author->getBooks(),'List can not have same book');
        $this->assertTrue($author->getBooks()->contains($book1),'Book1 should be in the book list.');
        $this->assertTrue($book1->getAuthors()->contains($author),'author should be connected to the book.');
    }

    /**
     * @depends testAddBook
     */
    public function testGetBooks():void
    {
        $book1 = new Book();
        $book2 = new Book();
        $author = new Author();
        $author->addBook($book1);
        $author->addBook($book2);
        $this->assertCount(2,$author->getBooks(),'List should have 2 books');
        $this->assertTrue($author->getBooks()->contains($book1),'Book1 should be in the book list');
        $this->assertTrue($author->getBooks()->contains($book2),'Book2 should be in the book list');
    }

    /**
     * @depends testAddBook
     */
    public function testRemoveBook():void
    {
        $author = new Author();
        $book = new Book();
        $author->addBook($book);
        $this->assertCount(1,$author->getBooks(),'List should have 1 books');
        $author->removeBook($book);
        $this->assertCount(0,$author->getBooks(),'List should be empty');
        $this->assertFalse($author->getBooks()->contains($book));
        $this->assertFalse($book->getAuthors()->contains($author));
    }

}