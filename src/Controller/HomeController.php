<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Entity\Article;
use App\Entity\Categories;

use App\Controller\methods;
use App\Form\SearchArticleType;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request, ArticleRepository $articleRepo): Response

    {
        $article = $this->getDoctrine()->getRepository(Article::class)->findByOrderByDate();
        $form = $this->createForm(SearchArticleType::class);
        $search = $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){   
            $article = $articleRepo->search($search->get('mots')->getData());
            
          // On recherche les articles correspondant aux mots-clés
        }
          
        

        return $this->render('home/index.html.twig', [
            'article'  => $article, 
            'form' => $form->createView()
            
        
        ]);
    }
   #[Route('/admin', name: 'admin')]  

    public function adminAccess(): Response
    {
        return $this->render('adminPage.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/a_showAll', name: 'a_showAll')]  

    public function a_showAll(Request $request , PaginatorInterface $paginator, ArticleRepository $articleRepo): Response
    {    
        $article = $this->getDoctrine()->getRepository(Article::class)->findAll(); 
      
        $auteur = $this->getDoctrine()->getRepository(Auteur::class)->findAll(); 
        $form = $this->createForm(SearchArticleType::class);
        $search = $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){   
         
            
            $article = $articleRepo->search($search->get('mots')->getData());
         }
       
         $article= $paginator->paginate($article,$request->query->getInt(key: 'page', default:1),limit:6);

       
        return $this->render('home/articleAll.html.twig', [
            'controller_name' => 'HomeController' , 'article'  => $article,
            'auteur'  => $auteur
            , 'form' => $form->createView()
        ]);
    }
    #[Route('/a_show/{id<\d+>}', name: 'a_show')]  

    public function a_show($id): Response
    { $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        
        return $this->render('home/article.html.twig', [
            'article'  => $article,
            
        ]);
    }
    #[Route('/c_showAll', name: 'c_showAll')]  

    public function c_showAll(): Response
    { 
        $categories = $this->getDoctrine()->getRepository(Categories::class)->findAll();
        return $this->render('home/categories.html.twig', [
            'controller_name' => 'HomeController' , 
            'categories'  => $categories,
        ]);
    }
    #[Route('/country_showAll', name: 'country_showAll')] 
    public function country_showAll(): Response
    { 
        $categories = $this->getDoctrine()->getRepository(Categories::class)->findAll();
        $article = $this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('home/country.html.twig', [
            
            'categories'  => $categories, 
            'article'  => $article,
        ]);
    }
    #[Route('/regions_showAll', name: 'regions_showAll')] 
    public function regions_showAll(): Response
    { 
        $categories = $this->getDoctrine()->getRepository(Categories::class)->findAll();
        return $this->render('home/regions.html.twig', [
            'controller_name' => 'HomeController' , 
            'categories'  => $categories,
        ]);
    }
    #[Route('/authors_showAll', name: 'authors_showAll')]  

    public function authors_showAll(Request $request , PaginatorInterface $paginator, ArticleRepository $articleRepo): Response
    {    
        $article = $this->getDoctrine()->getRepository(Article::class)->findAll(); 
      
        $auteur = $this->getDoctrine()->getRepository(Auteur::class)->findAll(); 
        $form = $this->createForm(SearchArticleType::class);
        $search = $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){   
         
            
            $article = $articleRepo->search($search->get('mots')->getData());
         }
       
         $article= $paginator->paginate($article,$request->query->getInt(key: 'page', default:1),limit:6);

       
        return $this->render('home/authors.html.twig', [
            'controller_name' => 'HomeController' , 'article'  => $article,
            'auteur'  => $auteur
            , 'form' => $form->createView()
        ]);
    }
    #[Route('/continent_showAll', name: 'continent_showAll')] 
    public function continent_showAll(): Response
    { 
        $categories = $this->getDoctrine()->getRepository(Categories::class)->findAll();
        return $this->render('home/continent.html.twig', [
            'controller_name' => 'HomeController' , 
            'categories'  => $categories,
        ]);
    }
   
}
