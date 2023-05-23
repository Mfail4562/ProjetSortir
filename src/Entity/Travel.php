<?php

namespace App\Entity;

use App\Repository\TravelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TravelRepository::class)]
class Travel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateStart = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $duration = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $limitDateSubscription = null;

    #[ORM\Column]

    private ?int $nbMaxTraveler = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $infos = null;

    #[ORM\ManyToOne(inversedBy: 'leaderTraveler')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $leader = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'subscriptionedTravels')]
    private Collection $subscriptionedTravelers;

    #[ORM\ManyToOne(inversedBy: 'travels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Status $status = null;

    #[ORM\ManyToOne(inversedBy: 'organisedTravels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Campus $campusOrganiser = null;

    #[ORM\ManyToOne(inversedBy: 'travelsPlace')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Place $place = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $cancelMessage = null;



    public function __construct()
    {
        $this->subscriptionedTravelers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(\DateTimeInterface $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getLimitDateSubscription(): ?\DateTimeInterface
    {
        return $this->limitDateSubscription;
    }

    public function setLimitDateSubscription(\DateTimeInterface $limitDateSubscription): self
    {
        $this->limitDateSubscription = $limitDateSubscription;

        return $this;
    }

    public function getNbMaxTraveler(): ?int
    {
        return $this->nbMaxTraveler;
    }

    public function setNbMaxTraveler(int $nbMaxTraveler): self
    {
        $this->nbMaxTraveler = $nbMaxTraveler;

        return $this;
    }

    public function getInfos(): ?string
    {
        return $this->infos;
    }

    public function setInfos(string $infos): self
    {
        $this->infos = $infos;

        return $this;
    }

    public function getLeader(): ?User
    {
        return $this->leader;
    }

    public function setLeader(?User $leader): self
    {
        $this->leader = $leader;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getSubscriptionedTravelers(): Collection
    {
        return $this->subscriptionedTravelers;
    }

    public function addSubscriptionedTraveler(User $subscriptionedTraveler): self
    {
        if (!$this->subscriptionedTravelers->contains($subscriptionedTraveler)) {
            $this->subscriptionedTravelers->add($subscriptionedTraveler);
            $subscriptionedTraveler->addSubscriptionedTravel($this);
        }

        return $this;
    }

    public function removeSubscriptionedTraveler(User|null $subscriptionedTraveler): self
    {
        if ($this->subscriptionedTravelers->removeElement($subscriptionedTraveler)) {
            $subscriptionedTraveler->removeSubscriptionedTravel($this);
        }

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCampusOrganiser(): ?Campus
    {
        return $this->campusOrganiser;
    }

    public function setCampusOrganiser(?Campus $campusOrganiser): self
    {
        $this->campusOrganiser = $campusOrganiser;

        return $this;
    }

    public function getPlace(): ?Place
    {

        return $this->place;
    }

    public function setPlace(?Place $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getCancelMessage(): ?string
    {
        return $this->cancelMessage;
    }

    public function setCancelMessage(?string $cancelMessage): self
    {
        $this->cancelMessage = $cancelMessage;

        return $this;
    }

}
