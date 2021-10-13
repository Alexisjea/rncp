<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Entity\Article;
use App\Form\AuteurType;
use App\Form\Article1Type;
use Cocur\Slugify\Slugify;
use App\Repository\AuthorRepository;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll()
        ]);
    }

    #[Route('/new', name: 'article_new', methods: ['GET', 'POST'])]
    
    public function new(Request $request): Response
    {
        $article = new Article();
    
        $form = $this->createForm(Article1Type::class, $article);
    
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slugify = new Slugify();
            $apercu = $form->get('preview')->getData();
            $fileName = $slugify->slugify($article->getTitle()) .  '.' . $apercu->guessExtension();

            try {
                $apercu->move($this->getParameter('article_assets_dir'), $fileName);
            } catch (FileException $e) {
                //... gèrer les exeptions survenues lors duu téléchargement de l'image 
            }
            $article->setPreview($fileName);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }


    #[Route('/{id}/edit', name: 'article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(Article1Type::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slugify = new Slugify();
            $apercu = $form->get('preview')->getData();
            $fileName = $slugify->slugify($article->getId()) .  '.' . $apercu->guessExtension();

            try {
                $apercu->move($this->getParameter('article_assets_dir'), $fileName);
            } catch (FileException $e) {
                //... gèrer les exeptions survenues lors duu téléchargement de l'image 
            }
            $article->setPreview($fileName);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
    }
}
