<?php

namespace App\Service;
use App\Entity\Character;
use App\Repository\CharacterRepository;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;


class CharacterService implements CharacterServiceInterface
{
    private $characterRepository;
    private $em;

    /**
     * CharacterService constructor.
     * @param $em
     */
    public function __construct(
        CharacterRepository $characterRepository,
        EntityManagerInterface $em)

    {
        $this->characterRepository = $characterRepository;
        $this->em = $em;
    }


    /**
     * {@inheritdoc}
     */
    public function create()
    {
        $character = new Character();
        $character
            ->setIdentifier(hash('sha1', uniqid()))
            ->setKind('Dame')
            ->setName('Amelie')
            ->setSurname('Serviteur des étoiles')
            ->setCaste('Elfe')
            ->setKnowledge('Arts')
            ->setIntelligence(120)
            ->setLife(12)
            ->setCreation(new DateTime())
        ;

        $this->em->persist($character);
        $this->em->flush();


        return $character;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll()
    {
        $charactersFinal = array();
        $characters = $this->characterRepository->findAll();
        foreach ($characters as $character) :
            $charactersFinal[] = $character->toArray();
        endforeach;
        return $charactersFinal;
    }

    /**
     * {@inheritdoc}
     */
    public function modify(Character $character)
    {
        $character
            ->setKind('Seigneur')
            ->setName('Gorthol')
            ->setSurname('Haume de terreur')
            ->setCaste('Chevalier')
            ->setKnowledge('Diplomatie')
            ->setIntelligence(110)
            ->setLife(13)
            ->setImage('/images/maeglin.jpg')
            ->setModification(new DateTime())
        ;

        $this->em->persist($character);
        $this->em->flush();

        return $character;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Character $character)
    {
        $this->em->remove($character);
        $this->em->flush();

        return $character;
    }
}