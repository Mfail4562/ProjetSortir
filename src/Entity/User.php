<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 13)]
    private ?string $phoneNumber = null;

    #[ORM\Column]
    private ?bool $admin = null;

    #[ORM\Column]
    private ?bool $actif = null;

    #[ORM\ManyToOne(inversedBy: 'students')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Campus $userCampus = null;

    #[ORM\OneToMany(mappedBy: 'leader', targetEntity: Travel::class, orphanRemoval: true)]
    private Collection $leaderTraveler;

    #[ORM\ManyToMany(targetEntity: Travel::class, inversedBy: 'subscriptionedTravelers')]
    private Collection $subscriptionedTravels;


    public function __construct()
    {
        $this->organisedTravels = new ArrayCollection();
        $this->travelsSubscriptioned = new ArrayCollection();
        $this->leaderTraveler = new ArrayCollection();
        $this->subscriptionedTravels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function isAdmin(): ?bool
    {
        return $this->admin;
    }

    public function setAdmin(bool $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getUserCampus(): ?Campus
    {
        return $this->userCampus;
    }

    public function setUserCampus(?Campus $userCampus): self
    {
        $this->userCampus = $userCampus;

        return $this;
    }

    /**
     * @return Collection<int, Travel>
     */
    public function getLeaderTraveler(): Collection
    {
        return $this->leaderTraveler;
    }

    public function addLeaderTraveler(Travel $leaderTraveler): self
    {
        if (!$this->leaderTraveler->contains($leaderTraveler)) {
            $this->leaderTraveler->add($leaderTraveler);
            $leaderTraveler->setLeader($this);
        }

        return $this;
    }

    public function removeLeaderTraveler(Travel $leaderTraveler): self
    {
        if ($this->leaderTraveler->removeElement($leaderTraveler)) {
            // set the owning side to null (unless already changed)
            if ($leaderTraveler->getLeader() === $this) {
                $leaderTraveler->setLeader(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Travel>
     */
    public function getSubscriptionedTravels(): Collection
    {
        return $this->subscriptionedTravels;
    }

    public function addSubscriptionedTravel(Travel $subscriptionedTravel): self
    {
        if (!$this->subscriptionedTravels->contains($subscriptionedTravel)) {
            $this->subscriptionedTravels->add($subscriptionedTravel);
        }

        return $this;
    }

    public function removeSubscriptionedTravel(Travel $subscriptionedTravel): self
    {
        $this->subscriptionedTravels->removeElement($subscriptionedTravel);

        return $this;
    }


}
