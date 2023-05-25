<?php

    namespace App\Test\Controller;

    use App\Entity\Place;
    use App\Repository\PlaceRepository;
    use Symfony\Bundle\FrameworkBundle\KernelBrowser;
    use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

    class PlaceControllerTest extends WebTestCase
    {
        private KernelBrowser $client;
        private PlaceRepository $repository;
        private string $path = '/place/';

        public function testIndex(): void
        {
            $crawler = $this->client->request('GET', $this->path);

            self::assertResponseStatusCodeSame(200);
            self::assertPageTitleContains('Place index');

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
                'place[name]' => 'Testing',
                'place[street]' => 'Testing',
                'place[latitude]' => 'Testing',
                'place[longitude]' => 'Testing',
                'place[city]' => 'Testing',
            ]);

            self::assertResponseRedirects('/place/');

            self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
        }

        public function testShow(): void
        {
            $this->markTestIncomplete();
            $fixture = new Place();
            $fixture->setName('My Title');
            $fixture->setStreet('My Title');
            $fixture->setLatitude('My Title');
            $fixture->setLongitude('My Title');
            $fixture->setCity('My Title');

            $this->repository->save($fixture, true);

            $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

            self::assertResponseStatusCodeSame(200);
            self::assertPageTitleContains('Place');

            // Use assertions to check that the properties are properly displayed.
        }

        public function testEdit(): void
        {
            $this->markTestIncomplete();
            $fixture = new Place();
            $fixture->setName('My Title');
            $fixture->setStreet('My Title');
            $fixture->setLatitude('My Title');
            $fixture->setLongitude('My Title');
            $fixture->setCity('My Title');

            $this->repository->save($fixture, true);

            $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

            $this->client->submitForm('Update', [
                'place[name]' => 'Something New',
                'place[street]' => 'Something New',
                'place[latitude]' => 'Something New',
                'place[longitude]' => 'Something New',
                'place[city]' => 'Something New',
            ]);

            self::assertResponseRedirects('/place/');

            $fixture = $this->repository->findAll();

            self::assertSame('Something New', $fixture[0]->getName());
            self::assertSame('Something New', $fixture[0]->getStreet());
            self::assertSame('Something New', $fixture[0]->getLatitude());
            self::assertSame('Something New', $fixture[0]->getLongitude());
            self::assertSame('Something New', $fixture[0]->getCity());
        }

        public function testRemove(): void
        {
            $this->markTestIncomplete();

            $originalNumObjectsInRepository = count($this->repository->findAll());

            $fixture = new Place();
            $fixture->setName('My Title');
            $fixture->setStreet('My Title');
            $fixture->setLatitude('My Title');
            $fixture->setLongitude('My Title');
            $fixture->setCity('My Title');

            $this->repository->save($fixture, true);

            self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

            $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
            $this->client->submitForm('Delete');

            self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
            self::assertResponseRedirects('/place/');
        }

        protected function setUp(): void
        {
            $this->client = static::createClient();
            $this->repository = static::getContainer()->get('doctrine')->getRepository(Place::class);

            foreach ($this->repository->findAll() as $object) {
                $this->repository->remove($object, true);
            }
        }
    }
