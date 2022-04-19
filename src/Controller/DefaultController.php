<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    //Route qui va lister l'ensemble de nos articles
    #[Route('/', name: 'liste_articles', methods: ['GET'])]
    public function listeArticles(): Response{

//        $url1 = $this->generateUrl('vue_article', ['id' => 1]);
//        $url2 = $this->generateUrl('vue_article', ['id' => 2]);
//        $url3 = $this->generateUrl('vue_article', ['id' => 3]);

        $articles = [
            [
                'nom' => 'Article 1',
                'id' => 1
            ],
            [
                'nom' => 'Article 2',
                'id' => 2
            ],
            [
                'nom' => 'Article 3',
                'id' => 3
            ]
        ];

        return $this->render('default/index.html.twig', [ //Par defaut dans symfony les vues se trouvent toutes dans templates

            'articles' => $articles

        ]);
    }

    // Visualiser unn article avec une route dynamique avec un parametre
    #[Route('/{id}', name: 'vue_article', requirements: ['id' => '\d+'], methods: ['GET'] )] //Là on veut que des entiers qui sont positifs avec le requirements et on choisit la méthode
    public function vueArticle($id)
    {
        return $this->render('default/vue.html.twig', [
            'id' => $id
        ]);


        //return new Response("<h1>Article ".$id."</h1> <p>Ceci est le contenu de l'article</p>");

        //dump($id); die;//https://127.0.0.1:8000/17
        //On voit qu'il nous dump le 17
    }
}
