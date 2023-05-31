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
        $spidersWeb = new Book();
        $spidersWeb->setISBN13('9780002261982');
        $spidersWeb->setTitle('Spiders Web');
        $spidersWeb->setThumbnail(
            'http://books.google.com/books/
        content?id=gA5GPgAACAAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api'
        );
        $spidersWeb->setYear('2000');
        $spidersWeb->setPageNumber('241');
        $spidersWeb->setAverageRating('3.83');
        $spidersWeb->setPrice('35.46');
        $spidersWeb->setGenre('action_adventure');
        $manager->persist($spidersWeb);

        $spiderDiary = new Book();
        $spiderDiary->setISBN13('9780060001537');
        $spiderDiary->setTitle('Diary of a Spider');
        $spiderDiary->setThumbnail(
            'http://books.google.com/books/
        content?id=UWvZo2pIhzMC&printsec=frontcover&img=1&zoom=1&source=gbs_api'
        );
        $spiderDiary->setYear('2005');
        $spiderDiary->setPageNumber('40');
        $spiderDiary->setAverageRating('4.25');
        $spiderDiary->setPrice('73.31');
        $spiderDiary->setGenre('fantasy');
        $manager->persist($spiderDiary);

        $spidersHouse = new Book();
        $spidersHouse->setISBN13('9780061137037');
        $spidersHouse->setTitle('Spiders House');
        $spidersHouse->setThumbnail(
            'http://books.google.com/books/
        content?id=3_jMWuT2OGEC&printsec=frontcover&img=1&zoom=1&source=gbs_api'
        );
        $spidersHouse->setYear('2006');
        $spidersHouse->setPageNumber('432');
        $spidersHouse->setAverageRating('4.03');
        $spidersHouse->setPrice('11.03');
        $spidersHouse->setGenre('action_adventure');
        $manager->persist($spidersHouse);

        $spidersMoon = new Book();
        $spidersMoon->setISBN13('9780743471329');
        $spidersMoon->setTitle('Diablo Moon of the Spider');
        $spidersMoon->setThumbnail(
            'http://books.google.com/books/
        content?id=GcY6PgAACAAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api'
        );
        $spidersMoon->setYear('2006');
        $spidersMoon->setPageNumber('336');
        $spidersMoon->setAverageRating('3.95');
        $spidersMoon->setPrice('28.57');
        $spidersMoon->setGenre('science_fiction');
        $manager->persist($spidersMoon);

        $strangers = new Book();
        $strangers->setISBN13('9780393321982');
        $strangers->setTitle('Strangers on a Train');
        $strangers->setThumbnail(
            'http://books.google.com/books/
        content?id=qbkfHZEygt4C&printsec=frontcover&img=1&zoom=1&source=gbs_api'
        );
        $strangers->setYear('2001');
        $strangers->setPageNumber('256');
        $strangers->setAverageRating('3.78');
        $strangers->setPrice('38.8');
        $strangers->setGenre('action_adventure');
        $manager->persist($strangers);

        $liars = new Book();
        $liars->setISBN13('9780743261982');
        $liars->setTitle('Liars and Saints');
        $liars->setThumbnail(
            'http://books.google.com/books/
        content?id=lgcngkRJnhsC&printsec=frontcover&img=1&zoom=1&source=gbs_api'
        );
        $liars->setYear('2004');
        $liars->setPageNumber('272');
        $liars->setAverageRating('3.59');
        $liars->setPrice('99.84');
        $liars->setGenre('action_adventure');
        $manager->persist($liars);

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
}
