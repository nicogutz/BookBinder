<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class BookCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Book::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->onlyOnIndex();
        yield Field::new('ISBN13');
        yield Field::new('title');
        yield Field::new('subtitle');
        yield Field::new('thumbnail');
        yield Field::new('description')->hideOnDetail();
        yield Field::new('year');
        yield Field::new('pageNumber');
        yield Field::new('averageRating');
        yield Field::new('ratingCount');
        yield Field::new('price');
        yield Field::new('genre');
        yield AssociationField::new('authors')
            ->autocomplete();
    }
}
