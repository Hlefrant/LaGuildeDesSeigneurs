<?php

namespace App\Controller;


use App\Entity\Player;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\PlayerServiceInterface;

class PlayerController extends AbstractController
{
    private $playerService;

    /**
     * PlayerController constructor.
     * @param $playerService
     */
    public function __construct(PlayerServiceInterface $playerService)
    {
        $this->playerService = $playerService;
    }

    /**
     * @Route("/player",
     *     name="player_redirect_index",
     *     methods={"GET", "HEAD"}
     *     )
     */
    public function redirectIndex()
    {
        return $this->redirectToRoute('player_index');
    }


    /**
     * @Route("/player/index",
     *     name="player_index",
     *     methods={"GET", "HEAD"}
     *     )
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('playerIndex', null);
        $players = $this->playerService->getAll();

        return new JsonResponse($players);
    }

    /**
     * @Route("/player/display/{identifier}",
     *     name="player_display",
     *     requirements={"identifier": "^([a-z0-9]{40})$"},
     *     methods={"GET", "HEAD"})
     * @return JsonResponse
     */

    public function display(Player $player)
    {
        $this->denyAccessUnlessGranted('playerDisplay', $player);

        return new JsonResponse($player->toArray());
    }

    //CREATE
    /**
     * @Route("/player/create",
     *     name="player_create",
     *     methods={"POST","HEAD"}
     *     )
     */

    public function create()
    {
        $this->denyAccessUnlessGranted('playerCreate', null);

        $player = $this->playerService->create();

        return new JsonResponse($player->toArray());
    }

    /**
     * @Route("/player/modify/{identifier}",
     *    name="player_modify",
     *    requirements={"identifier": "^([a-z0-9]{40}$)"},
     *    methods={"PUT", "HEAD"})
     */
    public function modify(Player $player)
    {
        $this->denyAccessUnlessGranted('playerModify', $player);
        $player = $this->playerService->modify($player);
        return new JsonResponse($player->toArray());
    }

    /**
     * @Route("/player/delete/{identifier}",
     *    name="player_delete",
     *    requirements={"identifier": "^([a-z0-9]{40}$)"},
     *    methods={"DELETE", "HEAD"})
     */
    public function delete(Player $player)
    {
        $this->denyAccessUnlessGranted('playerDelete', $player);
        $player = $this->playerService->delete($player);
        return new JsonResponse($player->toArray());
    }
}
