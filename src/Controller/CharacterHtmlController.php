<?php

namespace App\Controller;

use App\Entity\Character;
use App\Form\CharacterHtmlType;
use App\Service\CharacterServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CharacterHtmlController extends AbstractController
{
    private $characterService;

    public function __construct(CharacterServiceInterface $characterService)
    {
        $this->characterService = $characterService;
    }

    //INDEX
    /**
     * Displays available Characters in html
     *
     * @Route("/character/index.html",
     *     name="character_index_html",
     *     methods={"GET", "HEAD"}
     * )
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('characterIndex', null);

        return $this->render('character/index.html.twig', [
            'characters' => $this->characterService->getAll(),
        ]);
    }

    //CREATE
    /**
     * Creates the Character
     *
     * @Route("/character/create.html",
     *     name="character_create_html",
     *     methods={"GET", "POST", "HEAD"}
     * )
     */
    public function create(Request $request)
    {
        $this->denyAccessUnlessGranted('characterCreate', null);

        $character = new Character();
        $form = $this->createForm(CharacterHtmlType::class, $character);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->characterService->createFromHtml($character);

            return $this->redirectToRoute('character_display_html', array(
                'identifier' => $character->getIdentifier(),
            ));
        }

        return $this->render('character/create.html.twig', [
            'character' => $character,
            'form' => $form->createView(),
        ]);
    }

    //DISPLAY
    /**
     * Displays the Character
     *
     * @Route("/character/display/{identifier}.html",
     *     name="character_display_html",
     *     requirements={"identifier": "^([a-z0-9]{40})$"},
     *     methods={"GET", "HEAD"}
     * )
     * @Entity("character", expr="repository.findOneByIdentifier(identifier)")
     */
    public function display(Character $character)
    {
        $this->denyAccessUnlessGranted('characterDisplay', $character);

        return $this->render('character/display.html.twig', [
            'character' => $character,
        ]);
    }

    //MODIFY
    /**
     * Modifies the Character
     *
     * @Route("/character/modify/{identifier}.html",
     *     name="character_modify_html",
     *     requirements={"identifier": "^([a-z0-9]{40})$"},
     *     methods={"GET", "POST", "HEAD"}
     * )
     */
    public function modify(Request $request, Character $character)
    {
        $this->denyAccessUnlessGranted('characterModify', $character);

        $form = $this->createForm(CharacterHtmlType::class, $character);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->characterService->modifyFromHtml($character);

            return $this->redirectToRoute('character_display_html', array(
                'identifier' => $character->getIdentifier(),
            ));
        }

        return $this->render('character/modify.html.twig', [
            'character' => $character,
            'form' => $form->createView(),
        ]);
    }

    //DELETE
    /**
     * Deletes the Character
     *
     * @Route("/character/delete/{identifier}.html",
     *     name="character_delete_html",
     *     requirements={"identifier": "^([a-z0-9]{40})$"},
     *     methods={"GET", "DELETE", "HEAD"}
     * )
     */
    public function delete(Request $request, Character $character)
    {
        $this->denyAccessUnlessGranted('characterDelete', $character);

        if ($this->isCsrfTokenValid('delete'.$character->getIdentifier(), $request->request->get('_token'))) {
            $this->characterService->delete($character);
        }

        return $this->redirectToRoute('character_index_html');
    }
}
