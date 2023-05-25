<?php

    namespace App\DataFixtures;

    use App\Entity\Place;
    use Doctrine\Bundle\FixturesBundle\Fixture;
    use Doctrine\Persistence\ObjectManager;
    use Faker;

    class PlaceFixtures extends Fixture
    {
        public function load(ObjectManager $manager): void
        {
            $faker = Faker\Factory::create('fr_FR');


            for ($i = 0; $i < 50; $i++) {
                $place = new Place();
                $city = $this->getReference('city-' . rand(0, 4));
                $place->setName($faker->city)
                    ->setLatitude($faker->latitude)
                    ->setLongitude($faker->longitude)
                    ->setStreet($faker->streetName)
                    ->setCity($city);
                $manager->persist($place);
            }


            $manager->flush();
        }
    }
