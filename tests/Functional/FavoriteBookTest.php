<?php

namespace App\Tests;

use Facebook\WebDriver\WebDriverExpectedCondition;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\PantherTestCaseTrait;

class FavoriteBookTest extends WebTestCase
{
    use PantherTestCaseTrait;
    private $entityManager;
    private UserRepository|null $userRepo;
    private Client $client;
    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        // 获取数据库连接或使用应用程序提供的服务来访问数据库
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->userRepo = static::getContainer()->get(UserRepository::class);
        $this->client = static::createPantherClient(['port' => 8000]);

        //register test user
        $this->client->request('GET', '/register');
        // Validate a successful response and some content
        $array = [
            'registration_form[username]' => 'test',
            'registration_form[plainPassword]' => 'password',
            'registration_form[agreeTerms]' => true
        ];
        $this->client->submitForm('Register',$array);
    }
    public function testFavoriteButton()
    {
        $driver = $this->client->getWebDriver();
        $crawler = $this->client->request('GET', '/book_info/2');
        /**
         * TEST WITH NO_LOGIN :Alert Message
         */
        //  Verify if the access was successful
        $crawler->filter('#favorite_button')->click();
        // To wait for the appearance of a popup:
        $driver->wait()->until(
            WebDriverExpectedCondition::alertIsPresent()
        );
        //To retrieve the popup message
        $alert = $driver->switchTo()->alert();
        $alertText = $alert->getText();
        // To verify if the popup message matches the expected value
        $expectedMessage = 'Please login to add to favorites.';
        $this->assertEquals($expectedMessage, $alertText);
        // Close Alert
        $alert->accept();
        /**
         * TEST WITH LOGIN :NEW BOOK_LIKE RECORD
         */
        //first step : login
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->filter('form[method="post"]')->form();
         $form['username'] = 'test';
         $form['password'] = 'password';
         $crawler = $this->client->submit($form);

        // Request a specific page , and test the favorite_button
        $crawler = $this->client->request('GET', '/book_info/2');
        $crawler->filter('#favorite_button')->click();
        sleep(5);
        // test if the user_book update
        $user = $this->userRepo->findOneBy(['username'=>'test']);
        $likedBooks = $user->getBooks();
        $likedBook = $likedBooks[0];
        // verify likedbook is not null
        $this->assertNotNull($likedBook, 'First liked book not found.');
        //verify the correct book
        $this->assertEquals(2,$likedBook->getId());
        //clean the database
        $this->cleanup();
    }
    private function cleanup()
    {
        $user = $this->userRepo->findOneBy(['username' => 'test']);
        if ($user) {
            // 获取用户喜欢的书籍
            $attachedUser = $this->entityManager->merge($user);
            $this->entityManager->remove($attachedUser);
            $this->entityManager->flush();
        }
    }

}
