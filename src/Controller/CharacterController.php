<?php

namespace App\Controller;

use App\Entity\Character;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class CharacterController extends AbstractController
{
    /**
     * @Route("/character", name="character")
     */
    public function index()
    {
        return $this->render('character/index.html.twig', [
            'controller_name' => 'CharacterController',
        ]);
    }

    /**
     * @Route("/character/display", name="character_display")
     * @return JsonResponse
     */

    public function display(){
        $character = new Character();
        dump($character);
        die;

        return new JsonResponse($character->toArray());
    }
}
