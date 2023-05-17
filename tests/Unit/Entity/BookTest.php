<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Book;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class BookTest extends TestCase
{
    public function testBookCreate():void
    {
        $book = new Book();
        $book->setISBN13('978-3-16-148410-0');
        $book->setTitle('test_name');
        $book->setGenre('thriller');
        $book->setPrice(13.9);
        $book->setDescription('This test is balabalabalabala');
        $book->setAverageRating(3.5);
        $book->setPageNumber(100);
        $book->setRatingCount(50);
        $book->setYear(1930);
        $book->setSubtitle('test_subtitle');
        $book->setThumbnail('should be image of book cover');
        $this->assertEquals('978-3-16-148410-0',$book->getISBN13());
        $this->assertEquals('test_name',$book->getTitle());
        $this->assertEquals('thriller',$book->getGenre());
        $this->assertEquals('This test is balabalabalabala',$book->getDescription());
        $this->assertEquals('should be image of book cover',$book->getThumbnail());
        $this->assertEquals(13.9,$book->getPrice());
        $this->assertEquals(3.5,$book->getAverageRating());
        $this->assertEquals(100,$book->getPageNumber());
        $this->assertEquals(50,$book->getRatingCount());
        $this->assertEquals(1930,$book->getYear());
        $this->assertEquals('test_subtitle',$book->getSubtitle());
    }

    public function testAddUser():void
    {
        $book = new Book();
        $user = new User();
        $book->addUser($user);
        $this->assertCount(1,$book->getUsers());
        $this->assertTrue($book->getUsers()->contains($user));
        $this->assertTrue($user->getBooks()->contains($book),'user should be connected to the book.');
        $book->addUser($user);
        $this->assertCount(1,$book->getUsers(),'Book can not be liked by one user twice');
    }

    public function testRemoveUser():void
    {
        $book = new Book();
        $user = new User();
        $book->addUser($user);
        $this->assertCount(1,$book->getUsers());
        $this->assertTrue($book->getUsers()->contains($user));
        $book->removeUser($user);
        $this->assertFalse($user->getBooks()->contains($book));
        $this->assertFalse($book->getUsers()->contains($user));
    }

}