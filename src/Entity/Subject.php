<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\Assignment;

use App\Repository\SubjectRepository;

/**
 * Subject.
 */
#[ORM\Table(name: 'subject')]
#[ORM\Entity(repositoryClass: SubjectRepository::class)]
class Subject
{
    #[ORM\Id]
    #[ORM\Column()]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $name = null;

    /**
     * Many people can have many subjects.
     */
    #[ORM\JoinColumn(name: 'person_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Person::class, inversedBy: 'subjects', cascade: ['persist'])]
    private Collection $people;

    /**
     * One subect has many assignments.
     */
    #[ORM\JoinColumn(name: 'assignment_id', referencedColumnName: 'id')]
    #[ORM\OneToMany(targetEntity: Assignment::class, mappedBy: 'subject')]
    private Collection $assignments;

    /**
     * One Subject has One Log.
     */
    #[ORM\JoinColumn(name: 'log_id', referencedColumnName: 'id')]
    #[ORM\OneToOne(targetEntity: Log::class, cascade: ['persist', 'remove'])]
    private ?int $log = null;

    public function __construct()
    {
        $this->people = new ArrayCollection();
        $this->assignments = new ArrayCollection();
    }

    /**
     * Get id.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set name.
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    public function getLog(): ?Log
    {
        return $this->log;
    }

    public function setLog(?Log $log): self
    {
        $this->log = $log;

        return $this;
    }

    /**
     * @return Collection|Person[]
     */
    public function getPeople(): Collection
    {
        return $this->people;
    }

    public function addPerson(Person $person): self
    {
        if (!$this->people->contains($person)) {
            $this->people[] = $person;
            $person->addSubject($this);
        }

        return $this;
    }

    public function removePerson(Person $person): self
    {
        if ($this->people->contains($person)) {
            $this->people->removeElement($person);
            $person->removeSubject($this);
        }

        return $this;
    }

    /**
     * @return Collection|Assignment[]
     */
    public function getAssignments(): Collection
    {
        return $this->assignments;
    }

    public function addAssignment(Assignment $assigment): self
    {
        if (!$this->assignments->contains($assigment)) {
            $this->assignments[] = $assigment;
        }

        return $this;
    }

    public function removeAssignment(Assignment $assigment): self
    {
        if ($this->assignments->contains($assigment)) {
            $this->assignments->removeElement($assigment);
        }

        return $this;
    }
}
