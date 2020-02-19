<?php

namespace App\DataFixtures;

use App\Entity\Advertisement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $advertisment = new Advertisement();
        $advertisment->setTitle("Title Data");
        $advertisment->setPublished("Now");
        $advertisment->setSlug("slug-slug");
        $advertisment->setContent("Content content");
        $advertisment->setAuthor("Me");

        $manager->persist($advertisment);

        $advertisment = new Advertisement();
        $advertisment->setTitle("Title Data2");
        $advertisment->setPublished("Now2");
        $advertisment->setSlug("slug-slug2");
        $advertisment->setContent("Content content2");
        $advertisment->setAuthor("Me2");

        $manager->flush();
    }
}
