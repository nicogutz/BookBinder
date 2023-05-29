<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class SearchTest extends PantherTestCase
{
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
        //$this->takeScreenshotIfTestFailed();
    }

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
        //$this->takeScreenshotIfTestFailed();
    }

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
    }

    /**
     * @depends testSearchByTitle
     */
    public function testFilterByGener(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/search');
        $crawler->filter('#radio_title')->click();
        $crawler->filter('#Searchbar')->sendKeys('spider');
        $crawler->filter('#search-addon')->click();
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
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/search');
        $crawler->filter('#radio_title')->click();
        $crawler->filter('#Searchbar')->sendKeys('spider');
        $crawler->filter('#search-addon')->click();
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
        $crawler->filter('#dateFilterTo')->sendKeys('2005');;
        $crawler->filter('#filterButton')->click();
        $books = $crawler->filter('#bookList tr');
        $this->assertCount(2,$books);
        $this->assertSelectorTextContains('#bookList', 'Diary of a Spider');
        $this->assertSelectorTextContains('#bookList', 'Spiders Web');

    }

}
