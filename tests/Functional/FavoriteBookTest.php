<?php

namespace App\Tests;
use App\Entity\User;
use App\Entity\Book;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\PantherTestCaseTrait;
use Symfony\Component\Panther\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\ResponseInterface;

class FavoriteBookTest extends WebTestCase
{
    use PantherTestCaseTrait;
    private $entityManager;
    private UserRepository|null $userRepo;
    private Client $client;
    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        // Obtain database connection
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->userRepo = $this->entityManager->getRepository(User::class);
        $this->client = static::createPantherClient(['port' => 9090]);

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
        $form['username'] = 'test_user';
        $form['password'] = 'password';
        $crawler = $this->client->submit($form);
        /**
         * Test Add Favorite
         */
        // Request a specific page , and test the favorite_button
        $crawler = $this->client->request('GET', '/book_info/2');
        $crawler->filter('#favorite_button')->click();
        sleep(2);
        $crawler = $this->client->request('GET', '/');
        // check homepage books is not empty
        $this->assertNotEmpty($crawler->filter('.card'));
        // check if it is the correct book
        $user = $this->userRepo->findOneBy(['username'=>'test_user']);
        $likedBooks = $user->getBooks();
        /** @var Book $likedBook */
        $likedBook = $likedBooks[0];
        // verify likedbook is not null
        $this->assertNotNull($likedBook, 'First liked book not found.');
        //verify the correct book
        $this->assertEquals(2,$likedBook->getId());

        /**
         * Test Cancel Favorite
         */
        $crawler = $this->client->request('GET', '/book_info/2');
        $crawler->filter('#favorite_button')->click();
        sleep(2);
        $crawler = $this->client->request('GET', '/');
        // check homepage books is empty
        $this->assertEmpty($crawler->filter('.card'));


    }
}
