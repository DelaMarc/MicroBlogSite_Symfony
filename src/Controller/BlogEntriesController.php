<?php

namespace App\Controller;

use App\Entity\BlogEntry;
use App\Repository\BlogEntryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BlogEntriesController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/blogEntries', methods:['GET'], name: 'app_blog_entries')]
    public function index(): Response
    {
        $repository = $this->em->getRepository(BlogEntry::class);
        // findAll() SELECT * FROM blog_entry;
        $blogEntries = $repository->findAll();

        // findAll() SELECT * FROM blog_entry WHERE id = 5;
        //$blogEntries = $repository->find(7);

        // findby() SELECT * from blog_entry ORDER BY DESC;
        //$blogEntries = $repository->findBy([], ['id' => 'DESC']);

        // findOneBy() SELECT * from blog_entry WHERE id = 7 AND title = 'The Dark Knight' ORDER BY DESC;
        //$blogEntries = $repository->findOneBy(['id' => 7, 'title' => 'The Dark Knight'], ['id' => 'DESC']);

        // count() SELECT COUNT() from blog_entry WHERE id = 7;
        //$blogEntries = $repository->count(['id' => 7]);

        //$blogEntries = $repository->getClassName();

        return $this->render('blogEntries/index.html.twig', [
            'blogEntries' => $blogEntries,
        ]);
    }

    #[Route('/blog/{id}', methods:['GET'], name: 'show_blog_entry')]
    public function show($id): Response
    {
        $repository = $this->em->getRepository(BlogEntry::class);
        $blogEntry = $repository->find($id);

        return $this->render('blogEntries/show.html.twig', [
            'blogEntry' => $blogEntry,
        ]);
    }
}
