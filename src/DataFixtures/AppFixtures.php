<?php

namespace App\DataFixtures;

use App\Entity\Advertisement;
use App\Entity\Comment;
use App\Entity\User;
use App\Security\TokenGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use phpDocumentor\Reflection\Types\Self_;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var Factory
     */
    private $faker;

    /**
     * @var TokenGenerator
     */
    private $tokenGenerator;


    private const USERS = [
        [
            'username' => 'admin',
            'email' => 'admin@blog.com',
            'name' => 'Thomas Leard',
            'password' => 'admin1',
            'roles' => [User::ROLE_ADMIN],
            'enabled'=> true
        ],
        [
            'username' => 'john_doe',
            'email' => 'john@blog.com',
            'name' => 'John Doe',
            'password' => 'secret123#',
            'roles' => [User::ROLE_USER],
            'enabled'=> false
        ],
        [
            'username' => 'rob_smith',
            'email' => 'rob@blog.com',
            'name' => 'Rob Smith',
            'password' => 'secret123#',
            'roles' => [User::ROLE_AGENCY],
            'enabled'=> true
        ],
        [
            'username' => 'jenny_rowling',
            'email' => 'jenny@blog.com',
            'name' => 'Jenny Rowling',
            'password' => 'secret123#',
            'roles' => [User::ROLE_USER],
            'enabled'=> true
        ]
    ];


    public function __construct(UserPasswordEncoderInterface $passwordEncoder, TokenGenerator $tokenGenerator)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = Factory::create();
        $this->tokenGenerator =$tokenGenerator;
    }

    /**
     * Load data fixtures with the passed EntityManager
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadAdvertisements($manager);
        $this->loadComments($manager);
    }

    public function loadAdvertisements(ObjectManager $manager)
    {
        for ($i = 0; $i < 100; $i++) {
            $advertisement = new Advertisement();
            $advertisement->setTitle($this->faker->realText(30));
            $advertisement->setPublished($this->faker->dateTimeThisYear);
            $advertisement->setContent($this->faker->realText());

            $authorReference = $this->getRandomUserReference($advertisement);

            $advertisement->setAuthor($authorReference);
            $advertisement->setSlug($this->faker->slug);

            $this->setReference("advertisement_$i", $advertisement);

            $manager->persist($advertisement);
        }

        $manager->flush();
    }

    public function loadComments(ObjectManager $manager)
    {
        for ($i = 0; $i < 100; $i++) {
            for ($j = 0; $j < rand(1, 10); $j++) {
                $comment = new Comment();
                $comment->setContent($this->faker->realText());
                $comment->setPublished($this->faker->dateTimeThisYear);

                $authorReference = $this->getRandomUserReference($comment);

                $comment->setAuthor($authorReference);
                $comment->setAdvertisement($this->getReference("advertisement_$i"));

                $manager->persist($comment);
            }
        }

        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager)
    {
        foreach (self::USERS as $userFixture) {
            $user = new User();
            $user->setUsername($userFixture['username']);
            $user->setEmail($userFixture['email']);
            $user->setName($userFixture['name']);

            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $userFixture['password']
            ));
            $user->setRoles($userFixture['roles']);
            $user->setEnabled($userFixture['enabled']);

            if (!$userFixture['enabled']) {
                $user->setConfirmationToken(
                    $this->tokenGenerator->getRandomSecureToken()
                );
            }

            $this->addReference('user_' . $userFixture['username'], $user);

            $manager->persist($user);
        }

        $manager->flush();
    }

    protected function getRandomUserReference($entity): object
    {
        $randomUser = self::USERS[rand(0,3)];

        if ($entity instanceof Advertisement && !count(array_intersect($randomUser['roles'],
                [User::ROLE_ADMIN, User::ROLE_SUPERADMIN, User::ROLE_AGENCY])))
        {
            return $this->getRandomUserReference($entity);
        }

        if ($entity instanceof Comment && !count(array_intersect($randomUser['roles'],
                [User::ROLE_ADMIN, User::ROLE_SUPERADMIN, User::ROLE_AGENCY, User::ROLE_USER])))
        {
            return $this->getRandomUserReference($entity);
        }


        return $this->getReference('user_'.$randomUser['username']);
    }
}
