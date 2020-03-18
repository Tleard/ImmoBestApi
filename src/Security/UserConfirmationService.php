<?php


namespace App\Security;


use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserConfirmationService
{

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * UserConfirmationService constructor.
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $confirmationToken
     */
    public function confirmUser(string $confirmationToken)
    {
        $user = $this->userRepository->findOneBy(['confirmationToken' => $confirmationToken]);

        //User was found by confirmation token
        if ($user) {
            $user->setEnabled(true);
            $user->setConfirmationToken(null);
            $this->entityManager->flush();
        } else {
            throw new NotFoundHttpException();
        }
    }
}