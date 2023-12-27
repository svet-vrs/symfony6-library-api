<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Genre;
use App\Entity\Author;
use App\Form\BookFormType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BooksController extends AbstractController
{
    private $em;
    private $bookRepository;

    public function __construct(EntityManagerInterface $em, BookRepository $bookRepository)
    {
        $this->em = $em;
        $this->bookRepository = $bookRepository;
    }

    /**
     * Redirects the roure to main page
     */
    #[Route('/', name: 'index')]
    public function start(): Response
    {
        return $this->redirectToRoute('books');
    }

    /**
     * Shows main page with all books
     */
    #[Route('/books', name: 'books')]
    public function index(): Response
    {
        $books = $this->bookRepository->findAll();

        return $this->render('books/index.html.twig', [
            'books' => $books
        ]);
    }

    /**
     * Deletes book by id
     * @param $id - Book's id
     */
    #[Route('/books/delete/{id}', name: 'delete_book', methods: ['GET', 'DELETE'])]
    public function delete($id): Response
    {
//        $this->checkLoggedInUser($id);
        $book = $this->bookRepository->find($id);
        $this->em->remove($book);
        $this->em->flush();

        return $this->redirectToRoute('books');
    }

    /**
     * Creates new book with a data from form
     * @param Request $request - Request with data for a new book
     */
    #[Route('/books/create', name: 'create_book', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $book = new Book();
        $form = $this->createForm(BookFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getFormData($form, $book);
            return $this->redirectToRoute('books');
        }

        return $this->render('books/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Edits book by id
     * @param $id - Book's id
     * @param Request $request - Request with a new data for existing book
     */
    #[Route('/books/edit/{id}', name: 'edit_book')]
    public function edit($id, Request $request): Response
    {
//        $this->checkLoggedInUser($id);
        $book = $this->bookRepository->find($id);
        $form = $this->createForm(BookFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $book->removeAllGenres();
            $book->removeAllAuthors();
            $this->getFormData($form, $book);
            return $this->redirectToRoute('show_book', ['id' => $id]);
        }

        return $this->render('books/edit.html.twig', [
            'book' => $book,
            'form' => $form->createView()
        ]);
    }

    /**
     * Shows data about book by specified id
     * @param $id - Book's id
     */
    #[Route('/books/{id}', name: 'show_book', methods: ['GET'])]
    public function show($id): Response
    {
        $book = $this->bookRepository->find($id);

        return $this->render('books/show.html.twig', [
            'book' => $book
        ]);
    }
//    private function checkLoggedInUser($movieId) {
//        if($this->getUser() == null || $this->getUser()->getId() !== $movieId) {
//            return $this->redirectToRoute('movies');
//        }
//    }
    /**
     * Gets data from form and adds it in a specified parameters of book
     * @param $form - Form from where it takes data
     * @param $book - Book object to be created/changed
     */
    private function getFormData($form, $book): void
    {
        $book->setTitle($form->get('title')->getData());

        // separates genres titles from commas
        $genres_data = explode(',', $form->get('genres')->getData());
        // for each title add genre object to book
        foreach ($genres_data as $genre_title) {
            $genre = $this->em->getRepository(Genre::class)->findOneBy(array('title' => $genre_title));
            // creates new, if it doesn't exist
            if (!$genre) {
                $genre = new Genre();
                $genre->setTitle($genre_title);
                $this->em->persist($genre);
            }
            $book->addGenre($genre);
        }
        // separates authors name from commas
        $authors_data = explode(',', $form->get('authors')->getData());
        // for each name add author object to book
        foreach ($authors_data as $author_name) {
            $author = $this->em->getRepository(Author::class)->findOneBy(array('name' => $author_name));
            // creates new, if it doesn't exist
            if (!$author) {
                $author = new Author();
                $author->setName($author_name);
                $this->em->persist($author);
            }
            $book->addAuthor($author);
        }

        $this->em->persist($book);
        $this->em->flush();
    }
}