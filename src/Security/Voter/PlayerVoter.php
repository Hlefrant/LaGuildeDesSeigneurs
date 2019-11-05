<?php

namespace App\Security\Voter;

use App\Entity\Player;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use LogicException;

class PlayerVoter extends Voter
{
    public const PLAYER_DISPLAY = 'playerDisplay';
    public const PLAYER_CREATE = 'playerCreate';
    public const PLAYER_INDEX = 'playerIndex';
    public const PLAYER_MODIFY = 'playerModify';
    public const PLAYER_DELETE = 'playerDelete';

    private const ATTRIBUTES = array(
        self::PLAYER_DISPLAY,
        self::PLAYER_CREATE,
        self::PLAYER_INDEX,
        self::PLAYER_MODIFY,
        self::PLAYER_DELETE,
    );

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        if (null !== $subject) {
            return $subject instanceof Player && in_array($attribute, self::ATTRIBUTES);
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
            case self::PLAYER_DISPLAY:
                return $this->canDisplay($token, $subject);
                break;

            case self::PLAYER_CREATE:
                return $this->canCreate($token, $subject);
                break;

            case self::PLAYER_INDEX:
                return $this->canIndex($token, $subject);
                break;

            case self::PLAYER_MODIFY:
                return $this->canModify($token, $subject);
                break;

            case self::PLAYER_DELETE:
                return $this->canDelete($token, $subject);
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

    private function canIndex($token, $subject)
    {
        return true;
    }

    private function canModify($token, $subject)
    {
        return true;
    }

    private function canDelete($token, $subject)
    {
        return true;
    }
}
