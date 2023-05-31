<?php

namespace App\Tests\Functional;

use Symfony\Component\Panther\PantherTestCase;

class RegistrationAndLoginTest extends PantherTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createPantherClient();
        $this->client->followRedirects();
    }

    public function testRegistration(): void
    {
        $crawler = $this->client->request('GET','/register');
        $this->assertSelectorTextContains('h3', 'Register',
            'Fail to access to register page');
        $form = $crawler->filter('form[name="registration_form"]')->form();
        $form['registration_form[username]'] ->setValue('test');
        $form['registration_form[plainPassword]'] -> setValue('password');
        $form['registration_form[agreeTerms]'] -> setValue(true);
        $crawler->selectButton("Register")->click();
        $this->client->takeScreenshot('see.png');
        $this->assertSelectorTextContains('h1', 'Welcome to BookBinder!',
            'Fail to access to homepage');
    }

    public function testLogIn(): void
    {
        $crawler = $this->client->request('GET','/');
        $crawler->selectLink('Login')->click();
        $this->assertSelectorTextContains('h3', 'Log in',
            'Fail to access to login page');
        $this->client->takeScreenshot('see.png');
        $crawler = $this->client->refreshCrawler();
        $crawler->filter('input[name="username"]')->sendKeys('test');
        $crawler->filter('input[name="password"]')->sendKeys('password');
        $crawler->selectButton('Login')->click();
        $this->client->takeScreenshot('see.png');
        $this->assertSelectorTextContains('nav',
            'test | Logout',
            'Valid user should be able to login. ');
    }

    public function testLogOut(): void
    {
        $crawler = $this->client->request('GET','/');
        $this->client->takeScreenshot('see.png');
        $crawler->selectLink('test | Logout')->click();
        $crawler = $this->client->refreshCrawler();
        $this->assertSelectorTextContains('h1', 'Welcome to BookBinder!',
            'Fail to access to homepage');
        $this->assertSelectorTextContains('nav',
            'Login',
            'The user should be logged out ');
    }


}