<?php


namespace App\Service;


use App\Entity\Player;
use App\Repository\PlayerRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class PlayerService implements PlayerServiceInterface
{
    private $playerRepository;
    private $em;

    /**
     * PlayerService constructor.
     * @param $em
     */
    public function __construct(
        PlayerRepository $playerRepository,
        EntityManagerInterface $em)

    {
        $this->playerRepository = $playerRepository;
        $this->em = $em;
    }


    /**
     * {@inheritdoc}
     */
    public function create()
    {
        $player = new Player();
        $player
            ->setIdentifier(hash('sha1', uniqid()))
            ->setFirstname('Hugo')
            ->setLastname('Lefrant')
            ->setEmail('hugo.lefrant@gmail.com')
            ->setCreation(new DateTime())
            ->setMirian(500000)
        ;

        $this->em->persist($player);
        $this->em->flush();


        return $player;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll()
    {
        $playersFinal = array();
        $players = $this->playerRepository->findAll();
        foreach ($players as $player) :
            $playersFinal[] = $player->toArray();
        endforeach;
        return $playersFinal;
    }

    /**
     * {@inheritdoc}
     */
    public function modify(Player $player)
    {
        $player
            ->setFirstname('Hugo')
            ->setLastname('Lefrant')
            ->setEmail('hugo.lefrant@gmail.com')
            ->setModification(new DateTime())
            ->setMirian(500000)
        ;

        $this->em->persist($player);
        $this->em->flush();

        return $player;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Player $player)
    {
        $this->em->remove($player);
        $this->em->flush();

        return $player;
    }
}