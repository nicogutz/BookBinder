<?php

namespace App\Tests\Integration\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository|null $userRepo;
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->userRepo = static::getContainer()->get(UserRepository::class);
    }

    public function testRouting():void
    {
        $this->client->request('GET', '/login');
        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();
    }

    /**
     * @depends testRouting
     */
    public function testPageRendering():void
    {
        $crawler = $this->client->request('GET', '/login');
        $this->assertSelectorExists('h3','Log in');
        $this->assertCount(1, $crawler->filter('input[name="username"]'));
        $this->assertCount(1, $crawler->filter('input[name="password"]'));
    }

    /**
     * @depends testPageRendering
     */
    public function testSubmitValidData():void
    {
        $this->client->request('GET', '/login');
        $test_user = $this->userRepo->findOneBy(['username' => 'test_user']);
        $this->client->loginUser($test_user);
        $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('nav', 'test_user | Logout');
    }
}