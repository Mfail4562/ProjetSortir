<?php

    namespace App\Test\Controller;

    use App\Entity\City;
    use App\Repository\CityRepository;
    use Symfony\Bundle\FrameworkBundle\KernelBrowser;
    use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

    class CityControllerTest extends WebTestCase
    {
        private KernelBrowser $client;
        private CityRepository $repository;
        private string $path = '/city/';

        public function testIndex(): void
        {
            $crawler = $this->client->request('GET', $this->path);

            self::assertResponseStatusCodeSame(200);
            self::assertPageTitleContains('City index');

            // Use the $crawler to perform additional assertions e.g.
            // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
        }

        public function testNew(): void
        {
            $originalNumObjectsInRepository = count($this->repository->findAll());

            $this->markTestIncomplete();
            $this->client->request('GET', sprintf('%snew', $this->path));

            self::assertResponseStatusCodeSame(200);

            $this->client->submitForm('Save', [
                'city[name]' => 'Testing',
                'city[zipCode]' => 'Testing',
            ]);

            self::assertResponseRedirects('/city/');

            self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
        }

        public function testShow(): void
        {
            $this->markTestIncomplete();
            $fixture = new City();
            $fixture->setName('My Title');
            $fixture->setZipCode('My Title');

            $this->repository->save($fixture, true);

            $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

            self::assertResponseStatusCodeSame(200);
            self::assertPageTitleContains('City');

            // Use assertions to check that the properties are properly displayed.
        }

        public function testEdit(): void
        {
            $this->markTestIncomplete();
            $fixture = new City();
            $fixture->setName('My Title');
            $fixture->setZipCode('My Title');

            $this->repository->save($fixture, true);

            $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

            $this->client->submitForm('Update', [
                'city[name]' => 'Something New',
                'city[zipCode]' => 'Something New',
            ]);

            self::assertResponseRedirects('/city/');

            $fixture = $this->repository->findAll();

            self::assertSame('Something New', $fixture[0]->getName());
            self::assertSame('Something New', $fixture[0]->getZipCode());
        }

        public function testRemove(): void
        {
            $this->markTestIncomplete();

            $originalNumObjectsInRepository = count($this->repository->findAll());

            $fixture = new City();
            $fixture->setName('My Title');
            $fixture->setZipCode('My Title');

            $this->repository->save($fixture, true);

            self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

            $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
            $this->client->submitForm('Delete');

            self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
            self::assertResponseRedirects('/city/');
        }

        protected function setUp(): void
        {
            $this->client = static::createClient();
            $this->repository = static::getContainer()->get('doctrine')->getRepository(City::class);

            foreach ($this->repository->findAll() as $object) {
                $this->repository->remove($object, true);
            }
        }
    }
