<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * Saves a book to the database.
     * @param Book $entity
     * @param bool $flush
     * @return void
     */
    public function save(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Removes a book from the database.
     * @param Book $entity
     * @param bool $flush
     * @return void
     */
    public function remove(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Book[] Returns an array of Book objects
     * search by ISBN
     */
    public function findByISBN(string $value): array
    {

        return $this->createQueryBuilder('b')
            ->andWhere('b.ISBN13 LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Book[] Returns an array of Book objects
     * search by Title
     */
    public function findByTitle(string $value): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.title LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Book[] Returns an array of Book objects
     * search by Author
     */
    public function findByAuthor(string $value): array
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.authors', 'a')
            ->andWhere('a.name LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $id
     * @return Book|null Returns a book object
     * @throws NonUniqueResultException
     * search by ID
     */
    public function findByID(int $id): Book|null
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
