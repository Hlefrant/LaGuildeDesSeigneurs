<?php

namespace App\Service;
use App\Entity\Character;

class CharacterService implements CharacterServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function create()
    {
        $character = new Character();
        $character
            ->setKind('Dame')
            ->setName('Elendur')
            ->setSurname('Serviteur des étoiles')
            ->setCaste('Elfe')
            ->setKnowledge('Arts')
            ->setIntelligence(120)
            ->setLife(12)
        ;
        return $character;
    }
}