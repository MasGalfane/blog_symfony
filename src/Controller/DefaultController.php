<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    #[Route('/', name: 'liste_articles', methods: ['GET'])]                 //Route qui va lister l'ensemble de nos articles
    public function listeArticles(ArticleRepository $articleRepository,): Response{

//        $article = $articleRepository->findByTitle("Article n°1");
//        dump($article);die;

        $articles = $articleRepository->findAll();                          //On a crée notre varible article qui contient l'ensemble dz nos articles

        return $this->render('default/index.html.twig', [               //Par defaut dans symfony les vues se trouvent toutes dans templates
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

    #[Route("/article/ajouter", name: "ajout_article" )]
    public function ajouter(Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $manager){            //Là on a notre entité et on aimerait l'enregitrer en BDD on va donc utilisé l'entityManger et on le passe en parametre //Là on a une injection de dépendance nous permet d'injecter des classe à travers des parametres
//    dump($request); die;
        $form = $this->createFormBuilder()
        ->add('title', TextType::class, [
            'label' => "Article title"
            ]
        )
        ->add('content', TextareaType::class)
        ->add('createdAt', DateType::class, [         // changer date creationDate
            'widget' => 'single_text' //La on utilise un widget on peut changer par choice ou text
            ]
        )
            ->getForm();

        $form->handleRequest($request);  //Grace à cette fonction on lui dit d'aller attraper la requite qui est faite, quans on soumet le formulaire ça va faire une requete POST en envoyant les données

        if ($form->isSubmitted() && $form->isValid()) {
//            dump($form->getData()); die;
            $article = new Article();
            $article->setTitle($form->get('title')->getData());
            $article->setContent($form->get('content')->getData());
            $article->setCreatedAt($form->get('createdAt')->getData());
//        dump($article);die;

            $category = $categoryRepository->findOneBy([
                'name' => 'Sport'
            ]);
//            dump($category);die;
            $article->addCategory($category);

            $manager->persist($article);            //On a une nouvelle objet on le persist

            $manager->flush();

            return $this->redirectToRoute('liste_articles');
        }
        return $this->render('default/add.html.twig', [
            'form' => $form->createView()
            ]
        );

    }
}








//$article = new Article();
//$article->setTitle("Titre de l'article");
//$article->setContent("Ceci est le contenu de l'article");
//$article->setCreatedAt(new \DateTime());
//
//
//$manager->persist($article);
//
//$manager->flush();
//die;
//dump($article);die;