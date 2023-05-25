<?php

    namespace App\DataFixtures;

    use App\Entity\City;
    use Doctrine\Bundle\FixturesBundle\Fixture;
    use Doctrine\Persistence\ObjectManager;
    use Faker;

    class CityFixtures extends Fixture
    {
        public function load(ObjectManager $manager): void
        {
            $faker = Faker\Factory::create('fr_FR');

            for ($i = 0; $i < 5; $i++) {
                $city = new City();
                $city->setName($faker->city)
                    ->setZipCode($faker->numberBetween(01001, 95999));

                $this->addReference('city-' . $i, $city);

                $manager->persist($city);
            }

            $manager->flush();

        }
    }
