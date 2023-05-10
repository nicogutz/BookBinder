<?php

namespace App\Tests\Unit\Entity;


use App\Entity\Book;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testGetId()
    {
        $user = new User();
        $this->assertNull($user->getId());
    }

    public function testGetSetUsername()
    {
        $user = new User();
        $user->setUsername('test_user');
        $this->assertEquals('test_user', $user->getUsername());
    }

    public function testGetRoles()
    {
        $user = new User();
        $user->setRoles(['TEST_ROLE']);
        $this->assertEquals(['TEST_ROLE','ROLE_USER'], $user->getRoles());
    }

    public function testAddRemoveBook()
    {
        $user = new User();
        $book1 = $this->createMock(Book::class);
        $book2 = $this->createMock(Book::class);

        $this->assertCount(0, $user->getBooks());

        $user->addBook($book1);

        $this->assertCount(1, $user->getBooks());
        $this->assertTrue($user->getBooks()->contains($book1));

        $user->addBook($book2);

        $this->assertCount(2, $user->getBooks());
        $this->assertTrue($user->getBooks()->contains($book2));

        $user->removeBook($book1);

        $this->assertCount(1, $user->getBooks());
        $this->assertFalse($user->getBooks()->contains($book1));
        $this->assertTrue($user->getBooks()->contains($book2));

        $user->removeBook($book2);

        $this->assertCount(0, $user->getBooks());
        $this->assertFalse($user->getBooks()->contains($book1));
        $this->assertFalse($user->getBooks()->contains($book2));
    }
}