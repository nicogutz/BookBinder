<?php

namespace App\Tests\Integration\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testRoutingWithoutLogin(): void
    {
        // Request a specific page
        $this->client->request('GET', '/');
        // Validate a successful response and some content
        $this->assertResponseIsSuccessful('register is not accessible');
        $this->assertSelectorExists('main','You are not logged in yet!');
    }

    public function testRoutingWithLogin(): void
    {
        //TODO: get users' favorite books
    }
}