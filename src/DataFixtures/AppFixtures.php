<?php

namespace App\DataFixtures;

use App\Entity\Advertisement;
use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\User;
use App\Repository\ImageRepository;
use App\Security\TokenGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use http\Env\Request;
use phpDocumentor\Reflection\Types\Self_;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\File\File;

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

    /**
     * @var EntityManagerInterface
     */
    private $em;

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
            'email' => 'keyoyig299@mailezee.com',
            'name' => 'John Doe',
            'password' => 'secret123#',
            'roles' => [User::ROLE_USER],
            'enabled'=> false
        ],
        [
            'username' => 'rob_smith',
            'email' => 'huxtra@gmail.com',
            'name' => 'Rob Smith',
            'password' => 'secret123#',
            'roles' => [User::ROLE_AGENCY],
            'enabled'=> false
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


    public function __construct(UserPasswordEncoderInterface $passwordEncoder, TokenGenerator $tokenGenerator, EntityManagerInterface $em)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = Factory::create();
        $this->tokenGenerator =$tokenGenerator;
        $this->em = $em;
    }

    /**
     * Load data fixtures with the passed EntityManager
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadImages($manager);
        $this->loadUsers($manager);
        $this->loadAdvertisements($manager);
        $this->loadComments($manager);
    }

    public function loadImages(ObjectManager $manager)
    {
        for ($i = 1; $i < 16; $i++) {
            $image = new Image();
            $image->setFile(new File("/var/www/html/Immobest/ImmoBestApi/public/pictures/picture_" . $i . ".jpg"));
            $image->setUrl("picture_" . $i . ".jpg");
            $manager->persist($image);
        }
        $manager->flush();
    }

    public function loadAdvertisements(ObjectManager $manager)
    {
        for ($i = 0; $i < 100; $i++) {
            $advertisement = new Advertisement();
            $advertisement->setTitle($this->faker->realText(30));
            $advertisement->setPublished($this->faker->dateTimeThisYear);
            $advertisement->setContent($this->faker->realText());
            $advertisement->setPrice($this->faker->numberBetween(50,1000) *1000);
            $advertisement->setRooms($this->faker->numberBetween(1,6));
            $advertisement->setSquareMeter($this->faker->numberBetween(20,300));
            $advertisement->setCity($this->faker->city);
            $advertisement->setAddress($this->faker->address);
            $advertisement->addImage($this->em->getRepository('App:Image')->findOneBy(array("url" => "picture_" . rand(1,15) . ".jpg")));
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
