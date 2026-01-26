<?php

namespace tests;

use App\Entity\Film;
use PHPUnit\Framework\TestCase;

class FilmTest extends TestCase
{
    public function testSetTitle(): void
    {
        $film = new Film();
        $titre = "Interstellar";
        $film->setTitre($titre);
        $this->assertEquals($titre, $film->getTitre());
    }
}