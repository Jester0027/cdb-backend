<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Animal;
use App\Entity\Refuge;
use App\Entity\Picture;
use App\Entity\AnimalCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AnimalFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_BE');

        $numberOfCategories = 1;
        $numberOfRefuges = 1;
        $numberOfAnimals = 20;
        $categories = [];
        $refuges = [];
        $animals = [];

        for($i = 0; $i < $numberOfCategories; $i++) {
            $category = new AnimalCategory();
            $category->setName($faker->lastName());
            $manager->persist($category);
            array_push($categories, $category);
        }

        for($i = 0; $i < $numberOfRefuges; $i++) {
            $refuge = new Refuge();
            $latitude = $faker->latitude();
            $longitude = $faker->longitude();
            $refuge->setName($faker->company())
                ->setAddress($faker->streetAddress())
                ->setCity($faker->city())
                ->setZipCode((int)$faker->postcode())
                ->setCoordinates("$latitude , $longitude")
                ->setDescription($faker->realText())
            ;
            $manager->persist($refuge);

            array_push($refuges, $refuge);
        }

        for ($i = 0; $i < 20; $i++) {
            $gender = rand(1, 2);
            $animal = new Animal;
            $animal->setName($faker->firstName($gender === 1 ? "male" : "female"))
                ->setRace('default')
                ->setHeight(rand(40, 60))
                ->setWeight(rand(10, 50))
                ->setAge(rand(1, 20))
                ->setGender($gender === 1 ? true : false)
                ->setPrice(rand(25, 100))
                ->setAttitude($faker->realText())
                ->setDescription($faker->realText())
                ->setAnimalCategory($categories[rand(0, $numberOfCategories - 1)])
                ->setRefuge($refuges[rand(0, $numberOfRefuges - 1)])
            ;
            $manager->persist($animal);

            array_push($animals, $animal);
        }

        for($i = 0; $i < 25; $i++) {
            $picture = new Picture();
            $picture->setUrl($faker->imageUrl())
                ->setAnimal($animals[rand(0, $numberOfAnimals - 1)])
            ;
            $manager->persist($picture);
        }

        $manager->flush();
    }
}
