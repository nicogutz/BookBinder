<?php

namespace App\Tests\Integration\Controller;

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
        $crawler = $this->client->getCrawler();
        $form = $crawler->filter('form[method="post"]')->form();
        $form['username'] = 'test_user';
        $form['password'] = 'password';
        $crawler = $this->client->submit($form);
        $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('nav', 'test_user | Logout');
    }

    /**
     * @depends testSubmitValidData
     */
    public function testSubmitInvalidUsername():void
    {
        $this->client->request('GET', '/login');
        $test_user = $this->userRepo->findOneBy(['username' => 'test_user']);
        $crawler = $this->client->getCrawler();
        $form = $crawler->filter('form[method="post"]')->form();
        $form['username'] = 'wrong_user';
        $form['password'] = 'password';
        $crawler = $this->client->submit($form);
        $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('nav', 'Login');
    }

    /**
     * @depends testSubmitValidData
     */
    public function testSubmitInvalidPassword():void
    {
        $this->client->request('GET', '/login');
        $test_user = $this->userRepo->findOneBy(['username' => 'test_user']);
        $crawler = $this->client->getCrawler();
        $form = $crawler->filter('form[method="post"]')->form();
        $form['username'] = 'test_user';
        $form['password'] = 'wrongpassword';
        $crawler = $this->client->submit($form);
        $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('nav', 'Login');
    }
}