<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends Voter
{
    public const EDIT = 'TASK_EDIT';
    public const DELETE = 'TASK_DELETE';

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof \App\Entity\Task;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // Check si l'utilisateur est administrateur
        if (in_array('ROLE_ADMIN', $user->getRoles()) /*OR in_array('ROLE_ADMIN', $user->getRoles())*/ AND $subject->getUser() === NULL) {
            return true;
        }

        // ... (check les conditions et retourne vrai pour donner la permission) ...
        switch ($attribute) {
            // Logique qui détermine si l'utilisateur peut DELETE / EDIT
            case self::EDIT || self::DELETE:
                return $subject->getUser()->getId() === $user->getId();
                break;
        }

        return false;
    }
}
