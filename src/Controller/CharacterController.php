<?php

namespace App\Controller;

use App\Entity\Character;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\CharacterServiceInterface;

class CharacterController extends AbstractController
{
    private $characterService;

    /**
     * CharacterController constructor.
     * @param $characterService
     */
    public function __construct(CharacterServiceInterface $characterService)
    {
        $this->characterService = $characterService;
    }


    /**
     * @Route("/character", name="character")
     */
    public function index()
    {
        return $this->json(['path' => 'src/Controller/CharacterController.php']);
    }

    /**
     * @Route("/character/display", name="character_display", methods={"GET"})
     * @return JsonResponse
     */

    public function display(){
        $character = new Character();
        dump($character);

        return new JsonResponse($character->toArray());
    }

    //CREATE
    /**
     * @Route("/character/create",
     *     name="character_create",
     *     methods={"POST","HEAD"}
     *     )
     */

    public function create()
    {
        $character = $this->characterService->create();

        return new JsonResponse($character->toArray());
    }
}
