<?php

namespace App\Tests\Integration\Repository;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class UserRepositoryTest extends KernelTestCase
{
    private $entityManager;
    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testSave(): void
    {
        $user = new User();
        $user->setUsername('test');
        $user->setPassword('password');
        $userRepository = $this->entityManager->getRepository(User::class);
        $userRepository->save($user);
        $savedUser = $userRepository->findAll();
        $this->assertNotNull($savedUser);
        $this->assertSame(1, count($savedUser));
        //$this->assertSame('password', $savedUser->getPassword());
    }

    public function testRemove():void
    {
        $user = new User();
        $user->setUsername('test');
        $user->setPassword('password');
        $userRepository = $this->entityManager->getRepository(User::class);
        $userRepository->save($user);
        $savedUser = $userRepository->findAll();
        $this->assertNotNull($savedUser);
        $savedUser = $userRepository->findOneBy(['username'=>'test_user']);
        $userRepository->remove($savedUser);
        $savedUser = $userRepository->findAll();
        $this->assertNotNull($savedUser);
        $this->assertSame(1, count($savedUser));
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}