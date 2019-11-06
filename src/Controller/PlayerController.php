<?php

namespace App\Controller;

use App\Entity\Player;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Service\PlayerServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class PlayerController extends AbstractController
{
    private $playerService;

    /**
     * PlayerController constructor.
     *
     */
    public function __construct(PlayerServiceInterface $playerService)
    {
        $this->playerService = $playerService;
    }

    /**
     * Redirect to index route
     *
     * @Route("/player",
     *     name="player_redirect_index",
     *     methods={"GET", "HEAD"}
     *     )
     *
     * @SWG\Response(
     *     response=302,
     *     description="Redirect",
     * )
     * @SWG\Tag(name="Player")
     */
    public function redirectIndex()
    {
        return $this->redirectToRoute('player_index');
    }


    /**
     * display available Player
     *
     * @Route("/player/index",
     *     name="player_index",
     *     methods={"GET", "HEAD"}
     *     )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Success",
     *     @SWG\Schema(
     *      type="array",
     *      @SWG\Items(ref=@Model(type=Player::class))
     *      )
     * )
     * @SWG\Response(
     *     response=403,
     *     description="Access denied"
     * )
     * @SWG\Tag(name="Player")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('playerIndex', null);
        $players = $this->playerService->getAll();

        return new JsonResponse($players);
    }

    /**
     * Display Player
     *
     * @Route("/player/display/{identifier}",
     *     name="player_display",
     *     requirements={"identifier": "^([a-z0-9]{40})$"},
     *     methods={"GET", "HEAD"})
     * @Entity("player", expr="repository.findOneByIdentifier(identifier)")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Success",
     * @Model(type=Player::class)
     * )
     * @SWG\Response(
     *     response=403,
     *     description="Access denied"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     * @SWG\Tag(name="Player")
     */

    public function display(Player $player)
    {
        $this->denyAccessUnlessGranted('playerDisplay', $player);

        return new JsonResponse($player->toArray());
    }

    //CREATE
    /**
     * Create Player
     *
     * @Route("/player/create",
     *     name="player_create",
     *     methods={"POST","HEAD"}
     *     )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Success",
     * @Model(type=Player::class)
     * )
     * @SWG\Response(
     *     response=403,
     *     description="Access denied"
     * )
     * @SWG\Parameter(
     *     name="request",
     *     in="body",
     *     description="Data for the Player",
     *     required=true,
     *     @Model(type=App\Form\PlayerType::class)
     * )
     * @SWG\Tag(name="Player")
     */

    public function create(Request $request)
    {
        $this->denyAccessUnlessGranted('playerCreate', null);

        $player = $this->playerService->create($request->getContent());

        return new JsonResponse($player->toArray());
    }

    /**
     * Modify Player
     *
     * @Route("/player/modify/{identifier}",
     *    name="player_modify",
     *    requirements={"identifier": "^([a-z0-9]{40}$)"},
     *    methods={"PUT", "HEAD"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Success",
     * @Model(type=Player::class)
     * )
     * @SWG\Response(
     *     response=403,
     *     description="Access denied"
     * )
     * @SWG\Parameter(
     *     name="request",
     *     in="path",
     *     description="Data for the Player",
     *     required=true,
     *    type="string"
     * )
     *      * @SWG\Parameter(
     *     name="request",
     *     in="body",
     *     description="Data for the Player",
     *     required=true,
     *     @Model(type=App\Form\PlayerType::class)
     * )
     * @SWG\Tag(name="Player")
     */
    public function modify(Player $player, Request $request)
    {
        $this->denyAccessUnlessGranted('playerModify', $player);
        $player = $this->playerService->modify(
            $player,
            $request->getContent()
        );
        return new JsonResponse($player->toArray());
    }

    /**
     * Delete Player
     *
     * @Route("/player/delete/{identifier}",
     *    name="player_delete",
     *    requirements={"identifier": "^([a-z0-9]{40}$)"},
     *    methods={"DELETE", "HEAD"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Success",
     *     @SWG\Schema(
     *      @SWG\Property(property="delete", type="boolean")
     *  )
     * )
     * @SWG\Response(
     *     response=403,
     *     description="Access denied"
     * )
     * @SWG\Parameter(
     *     name="request",
     *     in="path",
     *     description="Data for the Player",
     *     required=true,
     *    type="string"
     * )
     * @SWG\Tag(name="Player")
     */
    public function delete(Player $player)
    {
        $this->denyAccessUnlessGranted('playerDelete', $player);
        $player = $this->playerService->delete($player);
        return new JsonResponse($player->toArray());
    }
}
