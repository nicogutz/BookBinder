<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\User;
use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class FunctionalFixtures extends Fixture implements FixtureGroupInterface
{
    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $entityManager;

    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager)
    {
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
    }

    public static function getGroups(): array
    {
        return ['group2'];
    }

    /**
     * This method is used to load the fixtures for the users.
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $this->loadBook_Author($manager,'Charles Osborne','9780002261982','Spiders Web',
        'http://books.google.com/books/content?id=gA5GPgAACAAJ&printsec=frontcover&img=1&zoom=1
        &source=gbs_api','2000','241','3.83','35.46','action_adventure');

        $this->loadBook_Author($manager,'Albert Speer','9780060001537','Diary of a Spider',
            'http://books.google.com/books/content?id=UWvZo2pIhzMC&printsec=frontcover&img=1&zoom=1
            &source=gbs_api','2005','40','4.25','73.31','fantasy');

        $this->loadBook_Author($manager, 'George Orwell', '9780061137037', 'Spiders House',
            'http://books.google.com/books/content?id=3_jMWuT2OGEC&printsec=frontcover&img=1&zoom=1
            &source=gbs_api', '2006', '432', '4.03', '11.03', 'action_adventure');

        $manager->flush();

        $this->loadBook_ExistedAuthor($manager, 'George Orwell', '9780743471329', 'Diablo Moon of the Spider',
            'http://books.google.com/books/content?id=GcY6PgAACAAJ&printsec=frontcover&img=1&zoom=1
            &source=gbs_api', '2006', '336', '3.95', '28.57', 'science_fiction');

        $this->loadBook_ExistedAuthor($manager, 'George Orwell', '9780393321982', 'Strangers on a Train',
            'http://books.google.com/books/content?id=qbkfHZEygt4C&printsec=frontcover&img=1&zoom=1
            &source=gbs_api', '2001', '256', '3.78', '38.8', 'action_adventure');

        $this->loadBook_ExistedAuthor($manager, 'George Orwell', '9780743261982', 'Liars and Saints',
            'http://books.google.com/books/content?id=lgcngkRJnhsC&printsec=frontcover&img=1&zoom=1
            &source=gbs_api', '2004', '272', '3.59', '99.84', 'action_adventure');

        $manager->flush();

        $user = new User();
        $book = $this->entityManager->getRepository(Book::class)->findByTitle('Spiders Web');
        $user->setUsername('test_user');
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'password');
        $user->setPassword($hashedPassword);
        $user->addBook($book[0]);
        $manager->persist($user);

        $user = new User();
        $user->setUsername('user');
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'password');
        $user->setPassword($hashedPassword);
        $manager->persist($user);

        $user = new User();
        $user->setUsername('admin');
        $user->setRoles(['ROLE_ADMIN']);
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'password');
        $user->setPassword($hashedPassword);
        $manager->persist($user);
        $manager->flush();
    }

    /**
     * @param $manager
     * @param $name
     * @param $ISBN
     * @param $title
     * @param $thumbnail
     * @param $year
     * @param $page_number
     * @param $rating
     * @param $price
     * @param $genre
     * @return void
     */
    private function loadBook_Author($manager, $name,$ISBN,$title,$thumbnail,$year,
                                     $page_number,$rating,$price,$genre): void
    {
        $author = new Author();
        $author->setName($name);

        $book = new Book();
        $book->setISBN13($ISBN);
        $book->setTitle($title);
        $book->setThumbnail($thumbnail);
        $book->setYear($year);
        $book->setPageNumber($page_number);
        $book->setAverageRating($rating);
        $book->setPrice($price);
        $book->setGenre($genre);
        $book->addAuthor($author);
        $manager->persist($author);
        $manager->persist($book);
    }

    private function loadBook_ExistedAuthor($manager,$name,$ISBN,$title,$thumbnail,$year,
                                             $page_number,$rating,$price,$genre): void
    {
        $author = $this->entityManager->getRepository(Author::class)->findOneBy(['name'=>$name]);

        $book = new Book();
        $book->setISBN13($ISBN);
        $book->setTitle($title);
        $book->setThumbnail($thumbnail);
        $book->setYear($year);
        $book->setPageNumber($page_number);
        $book->setAverageRating($rating);
        $book->setPrice($price);
        $book->setGenre($genre);
        $book->addAuthor($author);
        $manager->persist($book);
    }
}
