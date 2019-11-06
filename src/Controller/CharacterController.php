<?php

namespace App\Controller;

use App\Entity\Character;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Service\CharacterServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class CharacterController extends AbstractController
{
    private $characterService;

    /**
     * CharacterController constructor.
     *
     */
    public function __construct(CharacterServiceInterface $characterService)
    {
        $this->characterService = $characterService;
    }

    /**
     * Redirect to index route
     *
     * @Route("/character",
     *     name="character_redirect_index",
     *     methods={"GET", "HEAD"}
     *     )
     * @SWG\Response(
     *     response=302,
     *     description="Redirect",
     * )
     * @SWG\Tag(name="Character")
     */
    public function redirectIndex()
    {
        return $this->redirectToRoute('character_index');
    }


    /**
     * Displays available Characters
     *
     * @Route("/character/index",
     *     name="character_index",
     *     methods={"GET", "HEAD"}
     *     )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Success",
     *     @SWG\Schema(
     *      type="array",
     *      @SWG\Items(ref=@Model(type=Character::class))
     *      )
     * )
     * @SWG\Response(
     *     response=403,
     *     description="Access denied"
     * )
     * @SWG\Tag(name="Character")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('characterIndex', null);
        $characters = $this->characterService->getAll();

        return new JsonResponse($characters);
    }

    /**
     * Displays available Characters by intelligence
     *
     * @Route("/character/index/{intelligence}",
     *     name="character_index_intelligence",
     *     methods={"GET", "HEAD"}
     *     )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Success",
     *     @SWG\Schema(
     *      type="array",
     *      @SWG\Items(ref=@Model(type=Character::class))
     *      )
     * )
     * @SWG\Response(
     *     response=403,
     *     description="Access denied"
     * )
     * @SWG\Tag(name="Character")
     */
    public function indexIntelligence($intelligence)
    {
        $this->denyAccessUnlessGranted('characterIndexIntelligence', null);
        $characters = $this->characterService->getAllByIntelligence($intelligence);

        return new JsonResponse($characters);
    }

    /**
     * Display character
     *
     * @Route("/character/display/{identifier}",
     *     name="character_display",
     *     requirements={"identifier": "^([a-z0-9]{40})$"},
     *     methods={"GET", "HEAD"})
     * @Entity("character", expr="repository.findOneByIdentifier(identifier)")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Success",
     * @Model(type=Character::class)
     * )
     * @SWG\Response(
     *     response=403,
     *     description="Access denied"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     * @SWG\Tag(name="Character")
     */

    public function display(Character $character)
    {
        $this->denyAccessUnlessGranted('characterDisplay', $character);

        return new JsonResponse($character->toArray());
    }

    //CREATE
    /**
     * Create character
     *
     * @Route("/character/create",
     *     name="character_create",
     *     methods={"POST","HEAD"}
     *     )
     * @SWG\Response(
     *     response=200,
     *     description="Success",
     * @Model(type=Character::class)
     * )
     * @SWG\Response(
     *     response=403,
     *     description="Access denied"
     * )
     * @SWG\Parameter(
     *     name="request",
     *     in="body",
     *     description="Data for the Character",
     *     required=true,
     *     @Model(type=App\Form\CharacterType::class)
     * )
     * @SWG\Tag(name="Character")
     */

    public function create(Request $request)
    {
        $this->denyAccessUnlessGranted('characterCreate', null);

        $character = $this->characterService->create($request->getContent());

        return new JsonResponse($character->toArray());
    }

    /**
     * Modify character
     *
     * @Route("/character/modify/{identifier}",
     *    name="character_modify",
     *    requirements={"identifier": "^([a-z0-9]{40}$)"},
     *    methods={"PUT", "HEAD"})
     *
     *
     * @SWG\Response(
     *     response=200,
     *     description="Success",
     * @Model(type=Character::class)
     * )
     * @SWG\Response(
     *     response=403,
     *     description="Access denied"
     * )
     * @SWG\Parameter(
     *     name="request",
     *     in="path",
     *     description="Data for the Character",
     *     required=true,
     *    type="string"
     * )
     *      * @SWG\Parameter(
     *     name="request",
     *     in="body",
     *     description="Data for the Character",
     *     required=true,
     *     @Model(type=App\Form\CharacterType::class)
     * )
     * @SWG\Tag(name="Character")
     */
    public function modify(Character $character, Request $request)
    {
        $this->denyAccessUnlessGranted('characterModify', $character);
        $character = $this->characterService->modify($character, $request->getContent());
        return new JsonResponse($character->toArray());
    }

    /**
     * Delete character
     *
     * @Route("/character/delete/{identifier}",
     *    name="character_delete",
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
     *     description="Data for the Character",
     *     required=true,
     *    type="string"
     * )
     * @SWG\Tag(name="Character")
     */
    public function delete(Character $character)
    {
        $this->denyAccessUnlessGranted('characterDelete', $character);
        $character = $this->characterService->delete($character);
        return new JsonResponse($character->toArray());
    }
}
