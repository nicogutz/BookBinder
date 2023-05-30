<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\User;

class UserRepositoryTest extends KernelTestCase
{
    private $entityManager;
    private UserRepository|null $repository;

    /**
     * @return User
     */
    private function getUser(): User
    {
        $user = new User();
        $user->setUsername('testuser')
            ->setPassword('password');
        return $user;
    }

    protected function setUp(): void

    {
        $kernel = self::bootKernel();
        $this->assertSame('test', $kernel->getEnvironment());
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->repository = $this->entityManager->getRepository(User::class);
    }

    /**
     * @covers \App\Repository\UserRepository::save
     */
    public function testSaveUser(): void
    {
        $user = $this->getUser();
        $this->repository->save($user);
        $this->entityManager->flush();
        $savedUser = $this->repository->findOneBy(['username' => $user->getUsername()]);
        $this->assertEquals(
            $user->getUsername(),
            $savedUser->getUsername(),
            "The saved username should be identical to the actual one."
        );
    }

    /**
     * @covers \App\Repository\UserRepository::remove
     * @depends testSaveUser
     */
    public function testRemoveUser() {
        $user = new User();
        $user->setUsername('testuser')
            ->setPassword('password');
        $this->repository->save($user);
        $this->entityManager->flush();
        $this->repository->remove($user);
        $this->entityManager->flush();
        $removedUser = $this->repository->findOneBy(['username' => $user->getUsername()]);
        $this->assertNull($removedUser,"The removed user should not exist anymore.");
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
        $this->entityRepository = null;
    }
}
