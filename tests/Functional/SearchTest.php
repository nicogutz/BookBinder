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
        // Select the radio button for searching by author
        $crawler->filter('#radio_ISBN')->click();
        // Enter the author name in the search input
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
        // Select the radio button for searching by author
        $crawler->filter('#radio_title')->click();
        // Enter the author name in the search input
        $crawler->filter('#Searchbar')->sendKeys('Spiders Web');
        // Click the search button
        $crawler->filter('#search-addon')->click();
        $client->waitForElementToContain('#bookList','Spiders Web',5);
        // Assert the expected results on the page
        $this->assertSelectorTextContains('#bookList', 'Spiders Web');
        //$this->takeScreenshotIfTestFailed();
    }
}
