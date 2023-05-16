<?php

namespace App\Entity;

use App\Repository\CampusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CampusRepository::class)]
class Campus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'userCampus', targetEntity: User::class, orphanRemoval: true)]
    private Collection $students;

    #[ORM\OneToMany(mappedBy: 'campusOrganiser', targetEntity: Travel::class, orphanRemoval: true)]
    private Collection $organisedTravels;


    public function __construct()
    {
        $this->travels = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->students = new ArrayCollection();
        $this->organisedTravels = new ArrayCollection();
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

    /**
     * @return Collection<int, User>
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(User $student): self
    {
        if (!$this->students->contains($student)) {
            $this->students->add($student);
            $student->setUserCampus($this);
        }

        return $this;
    }

    public function removeStudent(User $student): self
    {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getUserCampus() === $this) {
                $student->setUserCampus(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Travel>
     */
    public function getOrganisedTravels(): Collection
    {
        return $this->organisedTravels;
    }

    public function addOrganisedTravel(Travel $organisedTravel): self
    {
        if (!$this->organisedTravels->contains($organisedTravel)) {
            $this->organisedTravels->add($organisedTravel);
            $organisedTravel->setCampusOrganiser($this);
        }

        return $this;
    }

    public function removeOrganisedTravel(Travel $organisedTravel): self
    {
        if ($this->organisedTravels->removeElement($organisedTravel)) {
            // set the owning side to null (unless already changed)
            if ($organisedTravel->getCampusOrganiser() === $this) {
                $organisedTravel->setCampusOrganiser(null);
            }
        }

        return $this;
    }

}
