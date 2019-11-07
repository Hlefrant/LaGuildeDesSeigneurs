<?php

namespace App\Service;

use App\Entity\Character;
use App\Repository\CharacterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use LogicException;
use App\Form\CharacterType;
use DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Event\CharacterEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CharacterService implements CharacterServiceInterface
{
    private $characterRepository;
    private $em;
    private $validator;
    private $formFactory;

    /**
     * CharacterService constructor.
     *
     */
    public function __construct(
        CharacterRepository $characterRepository,
        EntityManagerInterface $em,
        FormFactoryInterface $formFactory,
        ValidatorInterface $validator,
        EventDispatcherInterface $dispatcher
    ) {
        $this->characterRepository = $characterRepository;
        $this->em = $em;
        $this->formFactory = $formFactory;
        $this->validator = $validator;
        $this->dispatcher = $dispatcher;
    }



    /**
     * {@inheritdoc}
     */
    public function isEntityFilled(Character $character)
    {
        $errors = $this->validator->validate($character);
        if (count($errors) > 0) {
            throw new UnprocessableEntityHttpException((string) $errors . ' Missing data for Entity -> ' . json_encode($character->toArray()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function submit(Character $character, $formName, $data)
    {
        $dataArray = is_array($data) ? $data : json_decode($data, true);
        //Bad array
        if (null !== $data && !is_array($dataArray)) {
            throw new UnprocessableEntityHttpException('Submitted data is not an array -> ' . $data);
        }
        //Submits form
        $form = $this->formFactory->create($formName, $character, ['csrf_protection' => false]);
        $form->submit($dataArray, false);//With false, only submitted fields are validated
        //Gets errors
        $errors = $form->getErrors();
        foreach ($errors as $error) {
            throw new LogicException('Error ' . get_class($error->getCause()) . ' --> ' . $error->getMessageTemplate() . ' ' . json_encode($error->getMessageParameters()));
        }
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
    public function getAllByIntelligence(int $intelligence)
    {
        $charactersFinal = array();
        $characters = $this->characterRepository->findAll();
        foreach ($characters as $character) :
                if ($character->getIntelligence() >= $intelligence) {
                    $charactersFinal[] = $character->toArray();
                }
        endforeach;
        return $charactersFinal;
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

    /**
     * {@inheritdoc}
     */
    public function createFromHtml(Character $character)
    {
        $character
            ->setIdentifier(hash('sha1', uniqid()))
            ->setCreation(new DateTime())
            ->setModification(new DateTime())

        ;
        $this->isEntityFilled($character);

        //Dispatch event

        $event = new CharacterEvent($character);
        $this->dispatcher->dispatch($event, CharacterEvent::CHARACTER_CREATED);

        $this->em->persist($character);
        $this->em->flush();

        return $character;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyFromHtml(Character $character)
    {
        $this->isEntityFilled($character);
        $character
            ->setModification(new DateTime())
        ;

        $this->em->persist($character);
        $this->em->flush();

        return $character;
    }

    //ET MODIFIER LES 2 SUIVANTES POUR RESPECTER LE DRY
    /**
     * {@inheritdoc}
     */
    public function modify(Character $character, string $data)
    {
        $data = $this->submit($character, CharacterType::class, $data);

        return $this->modifyFromHtml($character);
    }

    /**
     * {@inheritdoc}
     */
    public function create(string $data)
    {
        $character = new Character();
        $this->submit($character, CharacterType::class, $data);

        return $this->createFromHtml(($character));
    }
}
