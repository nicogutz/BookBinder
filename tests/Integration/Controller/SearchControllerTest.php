<?php

namespace App\Tests\Integration\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SearchControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testRoutingWithoutLogin(): void
    {
        // Request a specific page
        $this->client->request('GET', '/search');
        // Validate a successful response and some content
        $this->assertResponseIsSuccessful('search is not accessible');
        $this->assertSelectorExists('main','You are not logged in yet!');
    }
}