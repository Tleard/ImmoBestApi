<?php

namespace App\DataFixtures;

use App\Entity\Advertisement;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
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
        $user2 = $this->getReference("Admin1");

        $advertisement = new Advertisement();
        $advertisement->setTitle("A first post!");
        $advertisement->setAuthor($user1);
        $advertisement->setContent("Some Content");
        $advertisement->setSlug("slug-slug");
        $advertisement->setPublished(new \DateTime());

        $manager->persist($advertisement);

        $advertisement = new Advertisement();
        $advertisement->setTitle("A second post!");
        $advertisement->setAuthor($user2);
        $advertisement->setContent("Some other Content");
        $advertisement->setSlug("slug-slug-slug");
        $advertisement->setPublished(new \DateTime());

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

        $comment = New Comment();

        $comment->setPublished(New \DateTime());
        $comment->setContent("Some Comment");
        $comment->setAuthor($user1);

        $manager->persist($comment);

        $comment->setPublished(New \DateTime());
        $comment->setContent("Some Comment");
        $comment->setAuthor($user2);

        $manager->persist($comment);

        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager)
    {
        $user = New User();

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
