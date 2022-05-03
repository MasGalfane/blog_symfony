<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/admin")]

class AdminController extends AbstractController
{
    #[Route("/article/ajouter", name: "ajout_article" )]
    #[Route("/article/{id}/edition", name: "edite_article", requirements: ['id' => '\d+'], methods: ['GET', 'POST'] )]
    public function ajouter(Article $article = null, Request $request, EntityManagerInterface $manager){            //Là on a notre entité et on aimerait l'enregitrer en BDD on va donc utilisé l'entityManger et on le passe en parametre //Là on a une injection de dépendance nous permet d'injecter des classe à travers des parametres

        // dump($logger); die;
        /* dump($article); die; */

        if($article === null){
            $article = new Article();
        }

        //Dès qu'on arrive dans notre formulaire
        //  $article = new Article();On va créer un nouveau article

        $form = $this->createForm(ArticleType::class, $article); //cette nouvelle article on va lu donner au formulaire et le formulaire va maper automatiquement les champs avec l'netité qu'on va lu passer

        $form->handleRequest($request);  //Grace à cette fonction on lui dit d'aller attraper la requite qui est faite, quand on soumet le formulaire ça va faire une requete POST en envoyant les données

        if ($form->isSubmitted() && $form->isValid()) {   //On verifit si le champ est soumet et bien valide
            //  dump($article); die;
            if($form->get('brouillon')->isClicked()){
                $article->setState('brouillon');
            }else{
                $article->setState('a publier');
            }

            if($article->getId() === null){
                $manager->persist($article);
            }

            $manager->flush();

            return $this->redirectToRoute('liste_articles');

        }

        return $this->render('default/add.html.twig', [
                'form' => $form->createView()
            ]
        );

    }


    /* On récupère tous nos articles brouillon */
    #[Route("/article/brouillon", name: "article_brouillon" )]
    public function brouillon(ArticleRepository $articleRepository){
        $articles = $articleRepository->findBy([
            'state' => 'brouillon'
        ]);

        return $this->render('default/index.html.twig', [
            'articles' => $articles,
            'brouillon' => true

        ]);
    }
}
