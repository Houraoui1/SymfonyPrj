<?php

namespace App\DataFixtures;

use App\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Jobfixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $Data = [
            'Web Developer',
            'Software Engineer',
            'Data Analyst',
            'UI/UX Designer',
            'Project Manager',
            'Network Administrator',
            'Database Administrator',
            'Systems Analyst',
            'Cybersecurity Analyst',
            'IT Support Specialist',
            'Quality Assurance Analyst',
            'Business Analyst',
            'DevOps Engineer',
            'Cloud Architect',
            'Mobile App Developer',
            'Game Developer',
            'AI/Machine Learning Engineer',
            'Blockchain Developer',
            'Digital Marketing Specialist',
            'Content Writer',
            'Graphic Designer',
        ];
        for($i=0;$i<count($Data);$i++){
              $job = new Job();
              $job->setDesignation($Data[$i]);
        $manager->persist($job);
        }
      

        $manager->flush();
    }
    }
