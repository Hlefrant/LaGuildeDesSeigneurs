<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Character;
use LogicException;

class CharacterVoter extends Voter
{
    public const CHARACTER_DISPLAY = 'characterDisplay';
    public const CHARACTER_CREATE = 'characterCreate';

    private const ATTRIBUTES = array(
        self::CHARACTER_DISPLAY,
        self::CHARACTER_CREATE,
    );

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject)
    {

        if (null !== $subject){
            return $subject instanceof Character && in_array($attribute, self::ATTRIBUTES);
        }
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, self::ATTRIBUTES);
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
//        if (!$user instanceof UserInterface) {
//            return false;
//        }


        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::CHARACTER_DISPLAY:
                return $this->canDisplay($token, $subject);
                break;

            case self::CHARACTER_CREATE:
            return $this->canCreate($token, $subject);
            break;
        }

        throw new LogicException('Invalid attribute: ' . $attribute);
    }

    /**
     * @param $token
     * @param $subject
     * @return bool
     */

    private function canDisplay($token, $subject)
    {
        return true;
    }

    private function canCreate($token, $subject)
    {
        return true;
    }
}
