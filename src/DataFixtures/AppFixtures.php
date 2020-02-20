<?php

namespace App\DataFixtures;

use App\Entity\Advertisement;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker\Factory;

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

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = Factory::create();
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadAdvertisement($manager);
        $this->loadComments($manager);
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function loadAdvertisement(ObjectManager $manager)
    {
        $user1 = $this->getReference("Admin");

        for ($i = 0; $i < 100; $i++){
            $advertisement = new Advertisement();
            $advertisement->setTitle($this->faker->realText(20));
            $advertisement->setAuthor($user1);
            $advertisement->setContent($this->faker->realText(35));
            $advertisement->setSlug($this->faker->slug);
            $advertisement->setPublished($this->faker->dateTimeThisMonth);

            $this->setReference("advertisement_$i", $advertisement);

            $manager->persist($advertisement);
        }
        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function loadComments(ObjectManager $manager)
    {
        $user1 = $this->getReference("Admin");
        $user2 = $this->getReference("Admin1");

        for ($i = 0; $i < 100; $i++) {
            $comment = new Comment();
            $comment->setPublished($this->faker->dateTime);
            $comment->setContent($this->faker->realText(30));
            $comment->setAuthor($user1);
            $comment->setAdvertisement($this->getReference("advertisement_$i"));
            $manager->persist($comment);
        }


        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager)
    {
        $user = new User();

        $user->setUsername("admin");
        $user->setName("Dylan Martin");
        $user->setEmail("dylanmartinpro@gmail.com");
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'admin'
        ));

        $this->addReference('Admin', $user);

        $manager->persist($user);

        $user->setUsername("admin1");
        $user->setName("Thomas Leard");
        $user->setEmail("thomas.leard@gmail.com");
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'admin'
        ));

        $this->addReference('Admin1', $user);

        $manager->persist($user);

        $manager->flush();
    }
}
