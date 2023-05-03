<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testRouting(): void
    {
        // This calls KernelTestCase::bootKernel(), and creates a
        // "client" that is acting as the browser
        $client = static::createClient();
        // Request a specific page
        $crawler = $client->request('GET', '/register');
        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form[name="registration_form"]');
        $form = $crawler->filter('form[name="registration_form"]')->form([
            'registration_form[username]' => 'test_user',
            'registration_form[plainPassword]' => 'password',
        ]);
        $client->submit($form);
        $this->assertResponseIsSuccessful('submit success');
        $client->followRedirects();
        $this->assertRouteSame('app_homepage');
    }
}