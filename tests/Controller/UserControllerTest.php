<?php

namespace App\Test\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $repository;
    private string $path = '/user/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(User::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('User index');

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
            'user[email]' => 'Testing',
            'user[roles]' => 'Testing',
            'user[password]' => 'Testing',
            'user[pseudo]' => 'Testing',
            'user[lastname]' => 'Testing',
            'user[firstname]' => 'Testing',
            'user[phoneNumber]' => 'Testing',
            'user[admin]' => 'Testing',
            'user[actif]' => 'Testing',
            'user[userCampus]' => 'Testing',
            'user[subscriptionedTravels]' => 'Testing',
        ]);

        self::assertResponseRedirects('/user/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new User();
        $fixture->setEmail('My Title');
        $fixture->setRoles('My Title');
        $fixture->setPassword('My Title');
        $fixture->setPseudo('My Title');
        $fixture->setLastname('My Title');
        $fixture->setFirstname('My Title');
        $fixture->setPhoneNumber('My Title');
        $fixture->setAdmin('My Title');
        $fixture->setActif('My Title');
        $fixture->setUserCampus('My Title');
        $fixture->setSubscriptionedTravels('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('User');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new User();
        $fixture->setEmail('My Title');
        $fixture->setRoles('My Title');
        $fixture->setPassword('My Title');
        $fixture->setPseudo('My Title');
        $fixture->setLastname('My Title');
        $fixture->setFirstname('My Title');
        $fixture->setPhoneNumber('My Title');
        $fixture->setAdmin('My Title');
        $fixture->setActif('My Title');
        $fixture->setUserCampus('My Title');
        $fixture->setSubscriptionedTravels('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'user[email]' => 'Something New',
            'user[roles]' => 'Something New',
            'user[password]' => 'Something New',
            'user[pseudo]' => 'Something New',
            'user[lastname]' => 'Something New',
            'user[firstname]' => 'Something New',
            'user[phoneNumber]' => 'Something New',
            'user[admin]' => 'Something New',
            'user[actif]' => 'Something New',
            'user[userCampus]' => 'Something New',
            'user[subscriptionedTravels]' => 'Something New',
        ]);

        self::assertResponseRedirects('/user/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getRoles());
        self::assertSame('Something New', $fixture[0]->getPassword());
        self::assertSame('Something New', $fixture[0]->getPseudo());
        self::assertSame('Something New', $fixture[0]->getLastname());
        self::assertSame('Something New', $fixture[0]->getFirstname());
        self::assertSame('Something New', $fixture[0]->getPhoneNumber());
        self::assertSame('Something New', $fixture[0]->getAdmin());
        self::assertSame('Something New', $fixture[0]->getActif());
        self::assertSame('Something New', $fixture[0]->getUserCampus());
        self::assertSame('Something New', $fixture[0]->getSubscriptionedTravels());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new User();
        $fixture->setEmail('My Title');
        $fixture->setRoles('My Title');
        $fixture->setPassword('My Title');
        $fixture->setPseudo('My Title');
        $fixture->setLastname('My Title');
        $fixture->setFirstname('My Title');
        $fixture->setPhoneNumber('My Title');
        $fixture->setAdmin('My Title');
        $fixture->setActif('My Title');
        $fixture->setUserCampus('My Title');
        $fixture->setSubscriptionedTravels('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/user/');
    }
}
