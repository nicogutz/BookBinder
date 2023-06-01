<?php

namespace App\Tests\Integration\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testRouting(): void
    {
        // Request a specific page
        $this->client->request('GET', '/');
        // Validate a successful response and some content
        $this->assertResponseIsSuccessful('register is not accessible');
    }

    /**
     * @depends testRouting
     */
    public function testHomepageWithoutLogin(): void
    {
        $this->client->request('GET', '/');
        // Validate a successful response and some content
        $this->assertResponseIsSuccessful('register is not accessible');
        $this->assertSelectorNotExists('h5','There should not books in the homepage without logging in');
    }

    /**
     * @depends testRouting
     */
    public function testBookDisplay():void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['username'=>'test_user']);
        $this->client->loginUser($user);
        $this->client->request('GET', '/');
        // Validate a successful response and some content
        $this->assertResponseIsSuccessful('register is not accessible');
        $this->assertSelectorTextContains('h5 ','Spiders Web',
            'The homepage should display book spider web after logged in');
    }

}