<?php

namespace App\Test\Controller;

use App\Entity\Travel;
use App\Repository\TravelRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TravelControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private TravelRepository $repository;
    private string $path = '/travel/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Travel::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Travel index');

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
            'travel[name]' => 'Testing',
            'travel[dateStart]' => 'Testing',
            'travel[duration]' => 'Testing',
            'travel[limitDateSubscription]' => 'Testing',
            'travel[nbMaxTraveler]' => 'Testing',
            'travel[infos]' => 'Testing',
            'travel[leader]' => 'Testing',
            'travel[subscriptionedTravelers]' => 'Testing',
            'travel[status]' => 'Testing',
            'travel[campusOrganiser]' => 'Testing',
            'travel[place]' => 'Testing',
        ]);

        self::assertResponseRedirects('/travel/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Travel();
        $fixture->setName('My Title');
        $fixture->setDateStart('My Title');
        $fixture->setDuration('My Title');
        $fixture->setLimitDateSubscription('My Title');
        $fixture->setNbMaxTraveler('My Title');
        $fixture->setInfos('My Title');
        $fixture->setLeader('My Title');
        $fixture->setSubscriptionedTravelers('My Title');
        $fixture->setStatus('My Title');
        $fixture->setCampusOrganiser('My Title');
        $fixture->setPlace('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Travel');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Travel();
        $fixture->setName('My Title');
        $fixture->setDateStart('My Title');
        $fixture->setDuration('My Title');
        $fixture->setLimitDateSubscription('My Title');
        $fixture->setNbMaxTraveler('My Title');
        $fixture->setInfos('My Title');
        $fixture->setLeader('My Title');
        $fixture->setSubscriptionedTravelers('My Title');
        $fixture->setStatus('My Title');
        $fixture->setCampusOrganiser('My Title');
        $fixture->setPlace('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'travel[name]' => 'Something New',
            'travel[dateStart]' => 'Something New',
            'travel[duration]' => 'Something New',
            'travel[limitDateSubscription]' => 'Something New',
            'travel[nbMaxTraveler]' => 'Something New',
            'travel[infos]' => 'Something New',
            'travel[leader]' => 'Something New',
            'travel[subscriptionedTravelers]' => 'Something New',
            'travel[status]' => 'Something New',
            'travel[campusOrganiser]' => 'Something New',
            'travel[place]' => 'Something New',
        ]);

        self::assertResponseRedirects('/travel/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getDateStart());
        self::assertSame('Something New', $fixture[0]->getDuration());
        self::assertSame('Something New', $fixture[0]->getLimitDateSubscription());
        self::assertSame('Something New', $fixture[0]->getNbMaxTraveler());
        self::assertSame('Something New', $fixture[0]->getInfos());
        self::assertSame('Something New', $fixture[0]->getLeader());
        self::assertSame('Something New', $fixture[0]->getSubscriptionedTravelers());
        self::assertSame('Something New', $fixture[0]->getStatus());
        self::assertSame('Something New', $fixture[0]->getCampusOrganiser());
        self::assertSame('Something New', $fixture[0]->getPlace());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Travel();
        $fixture->setName('My Title');
        $fixture->setDateStart('My Title');
        $fixture->setDuration('My Title');
        $fixture->setLimitDateSubscription('My Title');
        $fixture->setNbMaxTraveler('My Title');
        $fixture->setInfos('My Title');
        $fixture->setLeader('My Title');
        $fixture->setSubscriptionedTravelers('My Title');
        $fixture->setStatus('My Title');
        $fixture->setCampusOrganiser('My Title');
        $fixture->setPlace('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/travel/');
    }
}
