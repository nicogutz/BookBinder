<?php
namespace App\Tests\Integration\Controller;


use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testRouting(): void
    {
        // Request a specific page
        $crawler = $this->client->request('GET', '/register');
        // Validate a successful response and some content
        $this->assertResponseIsSuccessful('register is not accessible');
        $this->assertSelectorExists('form[name="registration_form"]');
        $buttons = $crawler->selectButton('Register');
        $this->assertCount(1,$buttons,"page should only have one register button");
    }

    public function testSubmitValidData():void
    {
        // Request a specific page
        $this->client->request('GET', '/register');
        // Validate a successful response and some content
        $array = [
            'registration_form[username]' => 'test',
            'registration_form[plainPassword]' => 'password',
            'registration_form[agreeTerms]' => true
        ];
        $this->client->submitForm('Register',$array);
        $this->assertResponseRedirects(
            '/',
            302,
            "Submitting a valid form should redirect the user to /"
        );
    }

    public function testEmptyAgreeTerms():void
    {
        // Request a specific page
        $this->client->request('GET', '/register');
        $this->assertResponseIsSuccessful();
        // test existed username
        $array = [
            'registration_form[username]' => 'test',
            'registration_form[plainPassword]' => 'password',
        ];
        $this->client->submitForm('Register',$array);
        $this->assertNotEquals(
            302,
            $this->client->getResponse()->getStatusCode(),
            "AgreeTerms must be filled."
        );
    }

    public function testInvalidPassword()
    {
        $this->client->request('GET', '/register');
        $this->assertResponseIsSuccessful();
        //test register without password
        $array = [
            'registration_form[username]' => 'test',
            'registration_form[agreeTerms]' => true
        ];
        $this->client->submitForm('Register',$array);
        $this->assertNotEquals(
            302,
            $this->client->getResponse()->getStatusCode(),
            "Information should have password."
        );
        //test register with password less than 6 characters
        $array = [
            'registration_form[username]' => 'test',
            'registration_form[plainPassword]' => 'passw',
            'registration_form[agreeTerms]' => true
        ];
        $this->client->submitForm('Register',$array);
        $this->assertNotEquals(
            302,
            $this->client->getResponse()->getStatusCode(),
            "Password should have at least 6 characters"
        );
        //test register with password over 4096 characters
        $array = [
            'registration_form[username]' => 'test',
            'registration_form[plainPassword]' => $this->make_password(4097),
            'registration_form[agreeTerms]' => true
        ];
        $this->client->submitForm('Register',$array);
        $this->assertNotEquals(
            302,
            $this->client->getResponse()->getStatusCode(),
            "Password should have at most 4096 characters"
        );
    }
    public function testInvalidUsername():void
    {
        // Request a specific page
        $this->client->request('GET', '/register');
        $this->assertResponseIsSuccessful();
        // test existed username
        $array = [
            'registration_form[username]' => 'test_user',
            'registration_form[plainPassword]' => 'password',
            'registration_form[agreeTerms]' => true
        ];
        $this->client->submitForm('Register',$array);
        $this->assertNotEquals(
            302,
            $this->client->getResponse()->getStatusCode(),
            "Username can't be the same as exited one "
        );
        //test register with blank username
        $array = [
            'registration_form[plainPassword]' => 'password',
            'registration_form[agreeTerms]' => true
        ];
        $this->client->submitForm('Register',$array);
        $this->assertNotEquals(
            302,
            $this->client->getResponse()->getStatusCode(),
            "The form can't be submitted if the username is empty. "
        );
    }


    //generate random length string
    private function make_password(int $length): string
    {
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $len = strlen($str)-1;
        $randstr = '';
        for ($i=0;$i<$length;$i++) {
            $num=mt_rand(0,$len);
            $randstr .= $str[$num];
        }
        return $randstr;
    }

}