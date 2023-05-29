<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class SearchTest extends PantherTestCase
{
    public function testSearchRouting(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/search');
        $this->assertSelectorTextContains('.col-md-9', 'Result');
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
        $client->waitForElementToContain('#bookList','Spiders Web',5);
        // Assert the expected results on the page
        $this->assertSelectorTextContains('#bookList', 'Spiders Web');
        //test fuzzy search by author
        $client->executeScript("document.getElementById('Searchbar').value = ''");
        $crawler->filter('#Searchbar')->sendKeys('orwell');
        $crawler->filter('#search-addon')->click();
        $client->waitForElementToContain('#bookList','The Mermaid Chair',5);
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
        $client->waitForElementToContain('#bookList','Spiders Web',5);
        // Assert the expected results on the page
        $this->assertSelectorTextContains('#bookList', 'Spiders Web');
        //test fuzzy search by ISBN
        $client->executeScript("document.getElementById('Searchbar').value = ''");
        $crawler->filter('#Searchbar')->sendKeys('1982');
        $crawler->filter('#search-addon')->click();
        $client->waitForElementToContain('#bookList','Strangers on a Train',5);
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
        $client->waitForElementToContain('#bookList','Spiders Web',5);
        // Assert the expected results on the page
        $this->assertSelectorTextContains('#bookList', 'Spiders Web');
        //test fuzzy search by title
        $client->executeScript("document.getElementById('Searchbar').value = ''");
        $crawler->filter('#Searchbar')->sendKeys('spider');
        $crawler->filter('#search-addon')->click();
        $client->waitForElementToContain('#bookList','Diary of a Spider',5);
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(4,$books);
        // Assert the expected results on the page
        $this->assertSelectorTextContains('#bookList', 'Spiders Web');
        $this->assertSelectorTextContains('#bookList', 'Diary of a Spider');
        $this->assertSelectorTextContains('#bookList', 'Spiders House');
        $this->assertSelectorTextContains('#bookList', 'Diablo Moon of the Spider');
    }

    /**
     * @depends testSearchByTitle
     */
    public function testFilterByGenre(): void
    {
        list($client, $crawler) = $this->searchBookWithTitleSpider();
        $client->waitForElementToContain('#bookList','Spiders Web',5);
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(4,$books);
        $this->assertSelectorTextContains('#bookList', 'Spiders Web');
        //test filtering by one genre
        $crawler->filter('#checkFantasy')->click();
        $crawler->filter('#filterButton')->click();
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(1,$books);
        $this->assertSelectorTextContains('#bookList', 'Diary of a Spider');
        //test filtering by many genres
        $crawler->filter('#checkSF')->click();
        $crawler->filter('#filterButton')->click();
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(2,$books);
        $this->assertSelectorTextContains('#bookList', 'Diary of a Spider');
        $this->assertSelectorTextContains('#bookList', 'Diablo Moon of the Spider');
    }

    /**
     * @depends testSearchByTitle
     */
    public function testFilterByYear(): void
    {
        list($client, $crawler) = $this->searchBookWithTitleSpider();
        $client->waitForElementToContain('#bookList','Spiders Web',5);
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(4,$books);
        $this->assertSelectorTextContains('#bookList', 'Spiders Web');
        //test filtering by YearFrom
        $crawler->filter('#dateFilterFrom')->sendKeys('2005');
        $crawler->filter('#filterButton')->click();
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(3,$books);
        $this->assertSelectorTextNotContains('#bookList','Spiders Web');
        //test filtering by YearTo
        $client->executeScript("document.getElementById('dateFilterFrom').value = ''");
        $crawler->filter('#dateFilterTo')->sendKeys('2005');
        $crawler->filter('#filterButton')->click();
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(2,$books);
        $this->assertSelectorTextContains('#bookList', 'Diary of a Spider');
        $this->assertSelectorTextContains('#bookList', 'Spiders Web');
    }

    /**
     * @depends testSearchByTitle
     */
    public function testFilterByPrice():void
    {
        list($client, $crawler) = $this->searchBookWithTitleSpider();
        $client->waitForElementToContain('#bookList','Spiders Web',5);
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(4,$books);
        $this->assertSelectorTextContains('#bookList', 'Spiders Web');
        //test filtering by Price
        $client->executeScript("document.querySelector('#priceSlider').value = 60");
        $crawler->filter('#filterButton')->click();
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(3,$books);
        $this->assertSelectorTextNotContains('#bookList','Diary of a Spider');
    }

    /**
     * @depends testFilterByGenre
     * @depends testFilterByPrice
     * @depends testFilterByYear
     */
    public function testFilterByAll(): void
    {
        list($client, $crawler) = $this->searchBookWithTitleSpider();
        $client->waitForElementToContain('#bookList','Spiders Web',5);
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(4,$books);
        $this->assertSelectorTextContains('#bookList', 'Spiders Web');
        //test filtering by Price
        $crawler->filter('#checkSF')->click();
        $crawler->filter('#checkA_A')->click();
        $crawler->filter('#dateFilterFrom')->sendKeys('2005');
        $crawler->filter('#dateFilterTo')->sendKeys('2006');
        $client->executeScript("document.querySelector('#priceSlider').value = 80");
        $crawler->filter('#filterButton')->click();
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(2,$books);
        $this->assertSelectorTextContains('#bookList', 'Spiders House');
        $this->assertSelectorTextContains('#bookList', 'Diablo Moon of the Spider');
    }

    /**
     * @depends testFilterByGenre
     * @depends testFilterByPrice
     * @depends testFilterByYear
     * @depends testSearchByAuthor
     * @depends testSearchByISBN
     */
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
        $client->waitForElementToContain('#bookList','Strangers on a Train',5);
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
        $client->waitForElementToContain('#bookList','Spiders Web',5);
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(4,$books);
        $this->assertSelectorTextContains('#bookList', 'Spiders Web');
        $crawler->filterXPath('//*[@id="bookList"]/tr[1]')->click();
        $currentUrl = $client->getCurrentURL();
        // Assert the expected URL or check if it matches a specific pattern
        $this->assertStringContainsString('/book_info/4329', $currentUrl);
    }

    /**
     * @return array
     */
    private function searchBookWithTitleSpider(): array
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/search');
        $crawler->filter('#radio_title')->click();
        $crawler->filter('#Searchbar')->sendKeys('spider');
        $crawler->filter('#search-addon')->click();
        return array($client, $crawler);
    }
}
