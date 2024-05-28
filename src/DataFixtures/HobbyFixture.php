<?php

namespace App\DataFixtures;

use App\Entity\Hobby;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HobbyFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $Data = [
            'Reading',
            'Writing',
            'Drawing',
            'Painting',
            'Playing an Instrument',
            'Singing',
            'Dancing',
            'Cooking',
            'Baking',
            'Gardening',
            'Hiking',
            'Camping',
            'Traveling',
            'Photography',
            'Fishing',
            'Gaming',
            'Watching Movies',
            'Playing Sports',
            'Yoga',
            'Meditation',
            'Volunteering',
            'Learning New Skills',
            'Collecting',
        ];
        for($i=0;$i<count($Data);$i++){
              $hobby = new Hobby();
              $hobby->setDesignation($Data[$i]);
        $manager->persist($hobby);
        }
      

        $manager->flush();
   
    }
}
