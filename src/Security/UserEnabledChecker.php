<?php


namespace App\Security;


use App\Entity\User;
use Exception;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserEnabledChecker implements UserCheckerInterface
{

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return new Exception("Object must be an User", 500);
        }

        if (!$user->isEnabled()) {
            throw new DisabledException();
        }
    }

    public function checkPreAuth(UserInterface $user)
    {
        // TODO: Implement checkPreAuth() method.
    }

}