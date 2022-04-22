<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    #[Route('/', name: 'liste_articles', methods: ['GET'])]              //Route qui va lister l'ensemble de nos articles
    public function listeArticles(ArticleRepository $articleRepository,): Response{

//        $article = $articleRepository->findByTitle("Article n°1");
//        dump($article);die;

        $articles = $articleRepository->findAll();          //On a crée notre varible article qui contient l'ensemble dz nos articles

        return $this->render('default/index.html.twig', [           //Par defaut dans symfony les vues se trouvent toutes dans templates
            'articles' => $articles

        ]);
    }

    #[Route('/{id}', name: 'vue_article', requirements: ['id' => '\d+'], methods: ['GET'] )]     // Visualiser unn article avec une route dynamique avec un parametre//Là on veut que des entiers qui sont positifs avec le requirements et on choisit la méthode
    public function vueArticle( Article $article){

        return $this->render('default/vue.html.twig', [
            'article' => $article
        ]);


        //return new Response("<h1>Article ".$id."</h1> <p>Ceci est le contenu de l'article</p>");
        //dump($id); die;//https://127.0.0.1:8000/17
        //On voit qu'il nous dump le 17
    }

    #[Route("/article/ajouter", name: "ajout_article")]
    public function ajouter(EntityManagerInterface $manager){            //Là on a notre entité et on aimerait l'enregitrer en BDD on va donc utilisé l'entityManger et on le passe en parametre //Là on a une injection de dépendance nous permet d'injecter des classe à travers des parametres


        $article = new Article();
        $article->setTitle("Titre de l'article");
        $article->setContent("Ceci est le contenu de l'article");
        $article->setCreatedAt(new \DateTime());


        $manager->persist($article);

        $manager->flush();
        die;
        //dump($article);die;
    }
}
