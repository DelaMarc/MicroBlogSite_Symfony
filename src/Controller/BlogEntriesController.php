<?php

namespace App\Controller;

use App\Entity\BlogEntry;
use App\Form\BlogEntryFormType;
use App\Repository\BlogEntryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

final class BlogEntriesController extends AbstractController
{
    private $em;
    private $fileSystem;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->fileSystem = new Filesystem();
    }

    private function deleteImage($imagePath)
    {
        if ($imagePath == null || $imagePath == ''){
            return;
        }
        $fullImagePath = $this->getParameter('kernel.project_dir').'/public'. $imagePath;
        // delete previous image if it exists
        if (file_exists($fullImagePath)){
            $this->fileSystem->remove($fullImagePath);
        }

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

    #[Route('/blogEntries/edit/{id}', name: 'edit_blog_entry')]
    public function edit($id, Request $request): Response
    {
        $blogEntry = $this->em->getRepository(BlogEntry::class)->find($id);
        $form = $this->createForm(BlogEntryFormType::class, $blogEntry);

        $form->handleRequest($request);
        $imagePath = $form->get('imagePath')->getData();
        if ($form->isSubmitted() && $form->isValid()){
            if ($imagePath){
                $oldImagePath = $blogEntry->getImagePath();
                $this->deleteImage($oldImagePath);
                $newFileName = uniqid().'.'.$imagePath->guessExtension();
                try {
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir').'/public/uploads',
                        $newFileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }
                $blogEntry->setImagePath('/uploads/'.$newFileName);
            }

            $blogEntry->setTitle($form->get('title')->getData());
            $blogEntry->setReleaseYear($form->get('releaseYear')->getData());
            $blogEntry->setDescription($form->get('description')->getData());
            $this->em->flush();
            return $this->redirectToRoute('app_blog_entries');

        }
        return $this->render('blogEntries/edit.html.twig', [
            'blogEntry' => $blogEntry,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/blogEntries/create', name: 'create_blog_entry')]
    public function create(Request $request): Response
    {
        $blogEntry = new BlogEntry();
        $form = $this->createForm(BlogEntryFormType::class, $blogEntry);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $newBlogEntry = $form->getData();
            $imagePath = $form->get('imagePath')->getData();
            if ($imagePath){
                $newFileName = uniqid().'.'.$imagePath->guessExtension();

                try {
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir').'/public/uploads',
                        $newFileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }
                $newBlogEntry->setImagePath('/uploads/'.$newFileName);
            }

            $this->em->persist($newBlogEntry);
            $this->em->flush();
            return $this->redirectToRoute('app_blog_entries');
        }
        return $this->render('blogEntries/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/blogEntries/delete/{id}', methods:['GET', 'DELETE'], name:'delete_blog_entry')]
    public function delete($id): Response
    {
        $repository = $this->em->getRepository(BlogEntry::class);
        $blogEntry = $repository->find($id);

        $this->deleteImage($blogEntry->getImagePath());
        $this->em->remove($blogEntry);
        $this->em->flush();
        return $this->redirectToRoute('app_blog_entries');
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
