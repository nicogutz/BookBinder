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
        $this->assertNull($user->getId(),'The user shouldn not have id before adding to database');
    }

    public function testGetSetUsername()
    {
        $user = new User();
        $user->setUsername('test_user');
        $this->assertEquals('test_user', $user->getUsername(),'The username should have the name: test_user');
    }

    public function testGetRoles()
    {
        $user = new User();
        $user->setRoles(['ROLE_TEST']);
        $this->assertEquals(['ROLE_TEST','ROLE_USER'], $user->getRoles(),'The username should have the role: ROLE_TEST, ROLE_USER');
    }

    public function testAddRemoveBook()
    {
        $user = new User();
        $book1 = $this->createMock(Book::class);
        $book2 = $this->createMock(Book::class);

        $this->assertCount(0, $user->getBooks(),'The new user should not have favorite book yet');

        $user->addBook($book1);

        $this->assertCount(1, $user->getBooks(),'The user should have book1. ');
        $this->assertTrue($user->getBooks()->contains($book1));

        $user->addBook($book2);

        $this->assertCount(2, $user->getBooks(),'The user should have book2. ');
        $this->assertTrue($user->getBooks()->contains($book2));

        $user->removeBook($book1);

        $this->assertCount(1, $user->getBooks(),'The user should have only one book. ');
        $this->assertFalse($user->getBooks()->contains($book1),'The user should not have book1');
        $this->assertTrue($user->getBooks()->contains($book2),'The user should have book2');

        $user->removeBook($book2);

        $this->assertFalse($user->getBooks()->contains($book1),'The user should not have book1');
        $this->assertFalse($user->getBooks()->contains($book2),'The user should not have book2');
        $this->assertCount(0, $user->getBooks(),'The user should not have any books');
    }
}