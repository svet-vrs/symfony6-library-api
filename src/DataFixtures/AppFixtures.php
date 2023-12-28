<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Genre;
use App\Entity\Book;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // -------------- Genres -------------
        $genre1 = new Genre();
        $genre1->setTitle('Novel');
        $manager->persist($genre1);
        $genre2 = new Genre();
        $genre2->setTitle('Fiction');
        $manager->persist($genre2);
        $genre3 = new Genre();
        $genre3->setTitle('Romance');
        $manager->persist($genre3);

        $this->addReference('genre_1',$genre1);
        $this->addReference('genre_2', $genre2);
        $this->addReference('genre_3', $genre3);

        $manager->flush();

        // -------------- Authors -------------

        $author = new Author();
        $author->setName('William Shakespeare');
        $manager->persist($author);

        $author2 = new Author();
        $author2->setName('Agatha Christie');
        $manager->persist($author2);

        $author3 = new Author();
        $author3->setName('Barbara Cartland');
        $manager->persist($author3);

        $this->addReference('author_1', $author);
        $this->addReference('author_2', $author2);
        $this->addReference('author_3', $author3);

        $manager->flush();

        // -------------- Users --------------

        $user = new User();
        $user->setEmail('admin@gmail.com');
        $user->setPassword('$2y$13$ZL5R.HF5gfj6FXxdmZM15OiTbOIx73ueVDpd7KjUHskN1CplkxZA.');
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        $user2 = new User();
        $user2->setEmail('user@gmail.com');
        $user2->setPassword('$2y$13$GBrz.syvG0jvU3UrietcHeSK8dX3vgdamxfdT84NvGi4r/Kd0je5W');
        $user2->setRoles(['ROLE_CLIENT']);
        $manager->persist($user2);

        $this->addReference('user_1', $user);
        $this->addReference('user_2', $user2);

        $manager->flush();

        // -------------- Books -------------

        $book = new Book();
        $book->setTitle('Good Omens');
        $book->addGenre($this->getReference('genre_1'));
        $book->addAuthor($this->getReference('author_1'));
        $book->addAuthor($this->getReference('author_2'));
        $book->addFavoriteBy($this->getReference('user_1'));

        $manager->persist($book);

        $book2 = new Book();
        $book2->setTitle('Roomies');
        $book2->addGenre($this->getReference('genre_2'));
        $book2->addGenre($this->getReference('genre_3'));
        $book2->addAuthor($this->getReference('author_3'));
        $book2->addFavoriteBy($this->getReference('user_1'));
        $book2->addFavoriteBy($this->getReference('user_2'));

        $manager->persist($book2);

        $book3 = new Book();
        $book3->setTitle('The Great Gatsby');
        $book3->addGenre($this->getReference('genre_1'));
        $book3->addGenre($this->getReference('genre_2'));
        $book3->addAuthor($this->getReference('author_3'));

        $manager->persist($book3);

        $manager->flush();
    }
}
