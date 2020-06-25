<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\EventTheme;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class EventFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create("fr_BE");

        $numberOfThemes = 5;
        $themes = [];

        for($i = 0; $i < $numberOfThemes; $i++) {
            $theme = new EventTheme();
            $theme->setName($faker->catchPhrase())
                ->setDescription($faker->realText())
            ;
            $manager->persist($theme);
            array_push($themes, $theme);
        }

        for($i = 0; $i < 20; $i++) {
            $event = new Event();
            $latitude = $faker->latitude();
            $longitude = $faker->longitude();
            $event->setTitle($faker->text(50))
                ->setEventDate($faker->dateTimeBetween('now', '6 months', 'Europe/Paris'))
                ->setAddress($faker->streetAddress())
                ->setCity($faker->city())
                ->setZipCode($faker->postcode())
                ->setCoordinates("$latitude,$longitude")
                ->setImageUrl($faker->imageUrl())
                ->setPrice(rand(10, 20))
                ->setDescription($faker->realText())
                ->setEventTheme($themes[rand(0, $numberOfThemes - 1)])
            ;
            $manager->persist($event);
        }

        $manager->flush();
    }
}
