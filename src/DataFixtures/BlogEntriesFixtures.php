<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\BlogEntry;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BlogEntriesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $entry = new BlogEntry();
        $entry->setTitle('The Dark Knight');
        $entry->setReleaseYear(2008);
        $entry->setDescription('This is the blog entry\'s content for The Dark Knight');
        $entry->setImagePath('https://cdn.pixabay.com/photo/2021/06/18/11/22/batman-6345897_960_720.jpg');

        // Add data to pivot table
        $entry->addActor($this->getReference('actor_1', Actor::class));
        $entry->addActor($this->getReference('actor_2', Actor::class));

        $manager->persist($entry);

        $entry2 = new BlogEntry();
        $entry2->setTitle('Avengers - Endgame');
        $entry2->setReleaseYear(2008);
        $entry2->setDescription('This is the blog entry\'s content for Avengers - Endgame');
        $entry2->setImagePath('https://pixabay.com/images/download/captain-america-5692937_1920.jpg');

        // Add data to pivot table
        $entry2->addActor($this->getReference('actor_3', Actor::class));
        $entry2->addActor($this->getReference('actor_4', Actor::class));
        
        $manager->persist($entry2);

        $manager->flush();
    }
}
