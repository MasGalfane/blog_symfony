<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Service\VerificationComment;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    #[Route('/', name: 'liste_articles', methods: ['GET'])]                 //Route qui va lister l'ensemble de nos articles
    public function listeArticles(ArticleRepository $articleRepository,): Response{

//        $article = $articleRepository->findByTitle("Article n°1");
//        dump($article);die;

        /* $articles = $articleRepository->findAll();   */                        //On a crée notre varible article qui contient l'ensemble dz nos articles
        $articles = $articleRepository->findBy([
            'state' => 'publier'
        ]);                         

        return $this->render('default/index.html.twig', [               //Par defaut dans symfony les vues se trouvent toutes dans templates
            'articles' => $articles,
            'brouillon' => false

        ]);
    }

    #[Route('/{id}', name: 'vue_article', requirements: ['id' => '\d+'], methods: ['GET', 'POST'] )]     // Visualiser unn article avec une route dynamique avec un parametre//Là on veut que des entiers qui sont positifs avec le requirements et on choisit la méthode
    public function vueArticle( Article $article, Request $request, EntityManagerInterface $manager, VerificationComment $verifyService, FlashBagInterface $session){        //Grace au params converter l'id qu'on va lui passer en parametre est automatiquement transformer en aricle si on lui passe l'id 51 il va nous recupérer l'article 51

//        dump($verifyService); die;

        $comment = new Comment();
        $comment->setArticle($article);

        $form =$this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
//            dump($comment);die;

            if ($verifyService->commentNoAuthorize($comment) === false){
                $manager->persist($comment);
                $manager->flush();

                return $this->redirectToRoute('vue_article', ['id' => $article->getId()]);
            }
            else{
                $session->add("danger", "Le commentaire contient un mot interdit");
            }

        }


        return $this->render('default/vue.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);


        //return new Response("<h1>Article ".$id."</h1> <p>Ceci est le contenu de l'article</p>");
        //dump($id); die;//https://127.0.0.1:8000/17
        //On voit qu'il nous dump le 17
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