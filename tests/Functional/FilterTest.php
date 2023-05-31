<?php

namespace App\Tests\Functional;

use Symfony\Component\Panther\PantherTestCase;

ini_set('max_execution_time',1500);
class FilterTest extends PantherTestCase
{
    public function testFilterByGenre(): void
    {
        list($client, $crawler) = $this->searchBookWithTitleSpider();
        //$client->waitForElementToContain('#bookList','Spiders Web',200);
        sleep(2);
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

    public function testFilterByYear(): void
    {
        list($client, $crawler) = $this->searchBookWithTitleSpider();
        //$client->waitForElementToContain('#bookList','Spiders Web',200);
        sleep(2);
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

    public function testFilterByPrice():void
    {
        list($client, $crawler) = $this->searchBookWithTitleSpider();
        //$client->waitForElementToContain('#bookList','Spiders Web',200);
        sleep(2);
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
        //$client->waitForElementToContain('#bookList','Spiders Web',200);
        sleep(2);
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