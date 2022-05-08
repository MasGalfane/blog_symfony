<?php

namespace App\Service;

use App\Entity\Comment;

class VerificationComment{
    public function commentNoAuthorize(Comment $comment){
        $unauthorized = [                                               //On a declarer un tableau de mots qui n'est pas autorisé
            "mauvais",
            "merde",
            "pourri",
            "con"
        ];

        foreach ($unauthorized as $word){                               //On boucle sur ce tableau
            if (strpos($comment->getContent(), $word)){                 //On verifie si le mot n'est pas dans le contenu
                return true;                                            //Si c'est la cas on retourne true
            }
        }

        return false;
    }
}