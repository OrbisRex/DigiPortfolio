<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\Assignment;

use App\Repository\TopicRepository;

/**
 * Topic.
 */
#[ORM\Table(name: 'topic')]
#[ORM\Entity(repositoryClass: TopicRepository::class)]
class Topic
{
    #[ORM\Id]
    #[ORM\Column()]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\JoinColumn(name: 'assignment_id', referencedColumnName: 'id')]
    #[ORM\OneToMany(targetEntity: Assignment::class, mappedBy: 'topic')]
    private Collection $assignments;

    /**
     * Many topics has one author.
     */
    #[ORM\JoinColumn(name: 'person_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(inversedBy: 'topics', cascade: ['persist'])]
    private ?Person $person = null;

    /**
     * One Topic has One Log.
     */
    #[ORM\JoinColumn(name: 'log_id', referencedColumnName: 'id')]
    #[ORM\OneToOne(targetEntity: Log::class, cascade: ['persist', 'remove'])]
    private ?int $log = null;

    public function __construct()
    {
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

    /**
     * Set description.
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     */
    public function getDescription(): ?string
    {
        return $this->description;
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

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): self
    {
        $this->person = $person;

        return $this;
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
}
