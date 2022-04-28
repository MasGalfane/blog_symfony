<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $state =['brouillon', 'publier'];

        for ($i=1; $i < 10; $i++) {             //A chaque fois on lui crée un nouveau article
            $article = new Article();
            $article->setTitle("Article n°".$i);            //On concataine avec l'index sur lequel on se retrouve
            $article->setContent("Ceci est le contenu de l'artcle ");
            $article->setState($state[array_rand($state)]);

            $date = new \DateTime();            //ça nous crée un nouveau datetime avec la date actuel
            $date->modify("-".$i. " days");         // à chaque fois on va lui enlever des jours

            $article->setCreatedAt($date);

            $this->addReference('article-'.$i, $article);

            $manager->persist($article);
        }

        $manager->flush();
    }
}
