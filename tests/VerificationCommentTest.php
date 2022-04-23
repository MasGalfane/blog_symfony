<?php

namespace App\Tests;

use App\Entity\Comment;
use App\Service\VerificationComment;
use PHPUnit\Framework\TestCase;


class VerificationCommentTest extends TestCase{

    protected  $comment;

    protected function setUp(): void
    {
        $this->comment = new Comment();
    }

    public function testContientMotInterdit(){

        $service = new VerificationComment();

        $this->comment->setContent("Ceci est un commentaire mauvais ");

        $result = $service->commentNoAuthorize($this->comment);

        $this->assertTrue($result);
    }

    public function testNeContientPasMotInterdi() {

        $service = new VerificationComment();

        $this->comment->setContent("Ceci est un commentaire ");

        $result = $service->commentNoAuthorize($this->comment);

        $this->assertFalse($result);

    }

}
