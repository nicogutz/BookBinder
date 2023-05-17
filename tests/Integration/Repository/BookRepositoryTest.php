<?php

namespace App\Tests\Integration\Repository;

use App\Entity\Book;
use App\Entity\User;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BookRepositoryTest extends KernelTestCase
{
    /**
     * @var BookRepository
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }



    public function testFindByISBN(): void
    {
        $bookRepository = $this->entityManager->getRepository(Book::class);
        //test euqal search
        $value_equal = 9780002261982;

        $result_euqal = $bookRepository->findByISBN($value_equal);
        $resultString_equal = json_encode($result_euqal);
        $array_equal = json_decode($resultString_equal, true);
        $id_equal = $array_equal[0]['id'];

        $this->assertIsArray($result_euqal);
        $this->assertNotEmpty($result_euqal);
        $this->assertEquals(2, $id_equal);
        //test Like Search
        $value_like = 97800022619;

        $result_like = $bookRepository->findByISBN($value_like);
        $resultString_like = json_encode($result_like);
        $array_like = json_decode($resultString_like, true);
        $id_like = $array_like[0]['id'];

        $this->assertIsArray($result_like);
        $this->assertNotEmpty($result_like);
        $this->assertEquals(2, $id_like);

        //test More return Search
        $value_like_multiple = 978000;

        $result_like_multiple = $bookRepository->findByISBN($value_like_multiple);
        $resultString_like_multiple = json_encode($result_like_multiple);
        $array_like_multiple = json_decode($resultString_like_multiple, true);
        //print the json_array , count array length
        /*
         * foreach ($array_like_multiple as $item) {
            $id = $item['id'];
            echo $id . "\n";
        }

        echo "Number of elements：" . $count;
         */
        $count = count($array_like_multiple);
        $this->assertIsArray($result_like_multiple);
        $this->assertNotEmpty($result_like_multiple);
        $this->assertEquals(56, $count);
    }
    public function testFindByTitle(): void
    {
        $bookRepository = $this->entityManager->getRepository(Book::class);
        //test euqal search
        $value_equal = "Spiders Web";
        $result_euqal = $bookRepository->findByTitle($value_equal);
        $resultString_equal = json_encode($result_euqal);
        $array_equal = json_decode($resultString_equal, true);
        $id_equal = $array_equal[0]['id'];
        $this->assertIsArray($result_euqal);
        $this->assertNotEmpty($result_euqal);
        $this->assertEquals(2, $id_equal);

        //test Like Search
        $value_like = "Spiders";
        $result_like = $bookRepository->findByTitle($value_like);
        $resultString_like = json_encode($result_like);
        $array_like = json_decode($resultString_like, true);
        $id_like = $array_like[0]['id'];

        $this->assertIsArray($result_like);
        $this->assertNotEmpty($result_like);
        $this->assertEquals(2, $id_like);

        //test More return Search
        $value_like_multiple = "S";
        $result_like_multiple = $bookRepository->findByTitle($value_like_multiple);
        $resultString_like_multiple = json_encode($result_like_multiple);
        $array_like_multiple = json_decode($resultString_like_multiple, true);
        //print the json_array , count array length
        /*
          foreach ($array_like_multiple as $item) {
            $id = $item['id'];
            echo $id . "\n";
        }

        echo "Number of elements：" . $count;
        */
        $count = count($array_like_multiple);
        $this->assertIsArray($result_like_multiple);
        $this->assertNotEmpty($result_like_multiple);
       $this->assertEquals(2525, $count);
    }
    public function testFindByAuthor(): void
    {
        $bookRepository = $this->entityManager->getRepository(Book::class);
        //test euqal search
        $value_equal = "Charles Osborne";
        $result_euqal = $bookRepository->findByAuthor($value_equal);
        $resultString_equal = json_encode($result_euqal);
        $array_equal = json_decode($resultString_equal, true);
        $id_equal = $array_equal[0]['id'];
        $this->assertIsArray($result_euqal);
        $this->assertNotEmpty($result_euqal);
        $this->assertEquals(2, $id_equal);

        //test Like Search
        $value_like = "Charles Osbor";
        $result_like = $bookRepository->findByAuthor($value_like);
        $resultString_like = json_encode($result_like);
        $array_like = json_decode($resultString_like, true);
        $id_like = $array_like[0]['id'];

        $this->assertIsArray($result_like);
        $this->assertNotEmpty($result_like);
        $this->assertEquals(2, $id_like);

        //test More return Search
        $value_like_multiple = "Charles";
        $result_like_multiple = $bookRepository->findByAuthor($value_like_multiple);
        $resultString_like_multiple = json_encode($result_like_multiple);
        $array_like_multiple = json_decode($resultString_like_multiple, true);
        //print the json_array , count array length
    /*
          foreach ($array_like_multiple as $item) {
            $id = $item['id'];
            echo $id . "\n";
        }

        echo "Number of elements：" . $count;
    */
        $count = count($array_like_multiple);
        $this->assertIsArray($result_like_multiple);
        $this->assertNotEmpty($result_like_multiple);
        $this->assertEquals(45, $count);
    }
    public function testFindById(): void
    {
        $bookRepository = $this->entityManager->getRepository(Book::class);
        //test euqal search
        $value_equal = 2;
        $result_euqal = $bookRepository->findById($value_equal);
        $resultString_equal = json_encode($result_euqal);
        $array_equal = json_decode($resultString_equal, true);
        $id_equal = $array_equal['id'];
        $this->assertNotEmpty($result_euqal);
        $this->assertEquals(2, $id_equal);

    }
}
