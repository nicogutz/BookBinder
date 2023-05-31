<?php

namespace App\Tests\Functional;

use Facebook\WebDriver\WebDriverExpectedCondition;
use Symfony\Component\Panther\PantherTestCase;

ini_set('max_execution_time',1500);
class SearchTest extends PantherTestCase
{
    public function testSearchRouting(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/search');
        $this->assertSelectorTextContains('.col-md-9', 'Result');
    }

    public function testSearchWithoutChoosingAttribute(): void
    {
        $client = static::createPantherClient();
        $driver = $client->getWebDriver();
        $crawler = $client->request('GET', '/search');
        // Enter the author name in the search input
        $crawler->filter('#Searchbar')->sendKeys('Charles Osborne');
        // Click the search button
        $crawler->filter('#search-addon')->click();
        $driver->wait()->until(
            WebDriverExpectedCondition::alertIsPresent()
        );
        $alert = $driver->switchTo()->alert();
        $alertText = $alert->getText();
        // To verify if the popup message matches the expected value
        $expectedMessage = 'Please select by what attribute you want to search the book';
        $this->assertEquals($expectedMessage, $alertText);
        // Close Alert
        $alert->accept();
    }

    /**
     * @depends testSearchRouting
     */
    public function testSearchByAuthor(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/search');
        // Select the radio button for searching by author
        $crawler->filter('#radio_author')->click();
        // Enter the author name in the search input
        $crawler->filter('#Searchbar')->sendKeys('Charles Osborne');
        // Click the search button
        $crawler->filter('#search-addon')->click();
        $client->waitForElementToContain('#bookList','Spiders Web',20);
        // Assert the expected results on the page
        $this->assertSelectorTextContains('#bookList', 'Spiders Web');
        //test fuzzy search by author
        $client->executeScript("document.getElementById('Searchbar').value = ''");
        $crawler->filter('#Searchbar')->sendKeys('orwell');
        $crawler->filter('#search-addon')->click();
        $client->waitForElementToContain('#bookList','The Mermaid Chair',20);
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(4,$books);
        // Assert the expected results on the page
        $this->assertSelectorTextContains('#bookList', 'The Mermaid Chair');
        $this->assertSelectorTextContains('#bookList', 'The Book of Imaginary Beings');
        $this->assertSelectorTextContains('#bookList', 'The Philosophy of Andy Warhol');
        $this->assertSelectorTextContains('#bookList', 'A Memoir of Jane Austen');
    }

    /**
     * @depends testSearchRouting
     */
    public function testSearchByISBN(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/search');
        // Select the radio button for searching by ISBN
        $crawler->filter('#radio_ISBN')->click();
        // Enter the ISBN in the search input
        $crawler->filter('#Searchbar')->sendKeys('9780002261982');
        // Click the search button
        $crawler->filter('#search-addon')->click();
        $client->waitForElementToContain('#bookList','Spiders Web',20);
        // Assert the expected results on the page
        $this->assertSelectorTextContains('#bookList', 'Spiders Web');
        //test fuzzy search by ISBN
        $client->executeScript("document.getElementById('Searchbar').value = ''");
        $crawler->filter('#Searchbar')->sendKeys('1982');
        $crawler->filter('#search-addon')->click();
        $client->waitForElementToContain('#bookList','Strangers on a Train',20);
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(3,$books);
        // Assert the expected results on the page
        $this->assertSelectorTextContains('#bookList', 'Spiders Web');
        $this->assertSelectorTextContains('#bookList', 'Strangers on a Train');
        $this->assertSelectorTextContains('#bookList', 'Liars and Saints');
    }

    /**
     * @depends testSearchRouting
     */
    public function testSearchByTitle(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/search');
        // Select the radio button for searching by title
        $crawler->filter('#radio_title')->click();
        // Enter the title in the search input
        $crawler->filter('#Searchbar')->sendKeys('Spiders Web');
        // Click the search button
        $crawler->filter('#search-addon')->click();
        $client->waitForElementToContain('#bookList','Spiders Web',20);
        // Assert the expected results on the page
        $this->assertSelectorTextContains('#bookList', 'Spiders Web');
        //test fuzzy search by title
        $client->executeScript("document.getElementById('Searchbar').value = ''");
        $crawler->filter('#Searchbar')->sendKeys('spider');
        $crawler->filter('#search-addon')->click();
        $client->waitForElementToContain('#bookList','Diary of a Spider',20);
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(4,$books);
        // Assert the expected results on the page
        $this->assertSelectorTextContains('#bookList', 'Spiders Web');
        $this->assertSelectorTextContains('#bookList', 'Diary of a Spider');
        $this->assertSelectorTextContains('#bookList', 'Spiders House');
        $this->assertSelectorTextContains('#bookList', 'Diablo Moon of the Spider');
    }

    public function testSearchWithInvalidInput(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/search');
        //test Invalid Input for title
        $crawler->filter('#radio_title')->click();
        $crawler->filter('#Searchbar')->sendKeys('5496%&*&');
        $crawler->filter('#search-addon')->click();
        $client->waitFor('#bookList');
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(0,$books);
        //test Invalid Input for ISBN
        $crawler->filter('#radio_ISBN')->click();
        $crawler->filter('#Searchbar')->sendKeys('5496%&*&');
        $crawler->filter('#search-addon')->click();
        $client->waitFor('#bookList');
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(0,$books);
        //test Invalid Input for author
        $crawler->filter('#radio_author')->click();
        $crawler->filter('#Searchbar')->sendKeys('5496%&*&');
        $crawler->filter('#search-addon')->click();
        $client->waitFor('#bookList');
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(0,$books);
        //test invalid input for year
        $crawler->filter('#radio_ISBN')->click();
        $client->executeScript("document.getElementById('Searchbar').value = ''");
        $crawler->filter('#Searchbar')->sendKeys('1982');
        $crawler->filter('#search-addon')->click();
        $client->waitForElementToContain('#bookList','Strangers on a Train',20);
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(3,$books);
        $crawler->filter('#dateFilterFrom')->sendKeys('5496%&*&');
        $crawler->filter('#filterButton')->click();
        $client->waitFor('#bookList');
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(0,$books);
        $client->executeScript("document.getElementById('dateFilterFrom').value = ''");
        $crawler->filter('#dateFilterTo')->sendKeys('5496%&*&');
        $crawler->filter('#filterButton')->click();
        $client->waitFor('#bookList');
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(0,$books);
    }

    /**
     * @depends testSearchByTitle
     */
    public function testRedirectToBookInfo(): void
    {
        list($client, $crawler) = $this->searchBookWithTitleSpider();
        $client->waitForElementToContain('#bookList','Spiders Web',20);
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(4,$books);
        $this->assertSelectorTextContains('#bookList', 'Spiders Web');
        $crawler->filterXPath('//*[@id="bookList"]/tr[4]')->click();
        $currentUrl = $client->getCurrentURL();
        // Assert the expected URL or check if it matches a specific pattern
        $this->assertStringContainsString('/book_info/2', $currentUrl);
    }

    /**
     * @depends testSearchByTitle
     */
    public function testOrderByRating():void
    {
        list($client, $crawler) = $this->searchBookWithTitleSpider();
        $client->waitForElementToContain('#bookList','Spiders Web',20);
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(4,$books);
        $this->assertSelectorTextContains('#bookList', 'Spiders Web');
        $crawler->filter('#OrderDropDown')->filterXPath('//option[text()="Rating"]')->click();
        $client->waitFor('#bookList tr');
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(4,$books);
        $firstBook = $crawler->filter('#bookList')->filter('tr')->first();
        // Assert that the first book is 'Diary of a Spider'
        $this->assertStringContainsString('Diary of a Spider', $firstBook->text());
    }

    /**
     * @depends testSearchByTitle
     */
    public function testOrderByName():void
    {
        list($client, $crawler) = $this->searchBookWithTitleSpider();
        $client->waitForElementToContain('#bookList','Spiders Web',20);
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(4,$books);
        $this->assertSelectorTextContains('#bookList', 'Spiders Web');
        $crawler->filter('#OrderDropDown')->filterXPath('//option[text()="Name"]')->click();
        $client->waitFor('#bookList tr');
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(4,$books);
        $firstBook = $crawler->filter('#bookList')->filter('tr')->first();
        // Assert that the first book is 'Diary of a Spider'
        $this->assertStringContainsString('Diablo Moon of the Spider', $firstBook->text());
        $secondBook = $crawler->filter('#bookList')->filter('tr')->eq(1);
        $this->assertStringContainsString('Diary of a Spider', $secondBook->text());
        $client->takeScreenshot('see.png');
    }

    /**
     * @depends testSearchByTitle
     */
    public function testOrderByDate():void
    {
        list($client, $crawler) = $this->searchBookWithTitleSpider();
        $client->waitForElementToContain('#bookList','Spiders Web',20);
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(4,$books);
        $this->assertSelectorTextContains('#bookList', 'Spiders Web');
        $crawler->filter('#OrderDropDown')->filterXPath('//option[text()="Date"]')->click();
        $client->waitFor('#bookList tr');
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(4,$books);
        $firstBook = $crawler->filter('#bookList')->filter('tr')->first();
        // Assert that the first book is 'Diary of a Spider'
        $this->assertStringContainsString('Spiders Web', $firstBook->text());
        $secondBook = $crawler->filter('#bookList')->filter('tr')->eq(1);
        $this->assertStringContainsString('Diary of a Spider', $secondBook->text());
    }

    /**
     * @depends testSearchByTitle
     */
    public function testOrderByPages():void
    {
        list($client, $crawler) = $this->searchBookWithTitleSpider();
        $client->waitForElementToContain('#bookList','Spiders Web',20);
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(4,$books);
        $this->assertSelectorTextContains('#bookList', 'Spiders Web');
            $crawler->filter('#OrderDropDown')->filterXPath('//option[text()="Pages"]')->click();
        $client->waitFor('#bookList tr');
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(4,$books);
        $firstBook = $crawler->filter('#bookList')->filter('tr')->first();
        // Assert that the first book is 'Diary of a Spider'
        $this->assertStringContainsString('Diary of a Spider', $firstBook->text());
        $secondBook = $crawler->filter('#bookList')->filter('tr')->eq(1);
        $this->assertStringContainsString('Spiders Web', $secondBook->text());
    }

    /**
     * @depends testSearchByISBN
     */
    public function testPaginationBookPerPage(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/search');
        // Select the radio button for searching by title
        $crawler->filter('#radio_ISBN')->click();
        // Enter the title in the search input
        $crawler->filter('#Searchbar')->sendKeys('23');
        // Click the search button
        $crawler->filter('#search-addon')->click();
        $client->waitFor('#bookList tr');
        //test each page shows 10 books
        $this->assertCount(10, $crawler->filter('#bookList')->filter('tr'));
    }

    /**
     * @return array
     */
    private function searchBookWithTitleSpider(): array
    {
        $client = static::createPantherClient(['port' => 9090]);
        $crawler = $client->request('GET', '/search');
        $crawler->filter('#radio_title')->click();
        $crawler->filter('#Searchbar')->sendKeys('spider');
        $crawler->filter('#search-addon')->click();
        return array($client, $crawler);
    }
}
