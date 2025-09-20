<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\Person;

use App\Repository\CriterionRepository;

/**
 * Criteria.
 */
#[ORM\Table(name: 'criterion')]
#[ORM\Entity(repositoryClass: CriterionRepository::class)]
class Criterion
{
    #[ORM\Id]
    #[ORM\Column()]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    /**
     * Criteria have many descriptors.
     */
    #[ORM\JoinTable(name: 'criteria_descriptors')]
    #[ORM\ManyToMany(targetEntity: Descriptor::class, inversedBy: 'criteria', cascade: ['persist'])]
    private Collection $descriptors;

    /**
     * Many criterion belongs to one author.
     */
    #[ORM\JoinColumn(name: 'person_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(inversedBy: 'criteria')]
    private ?Person $author = null;

    /**
     * One Criterion has One Log.
     */
    #[ORM\JoinColumn(name: 'log_id', referencedColumnName: 'id')]
    #[ORM\OneToOne(targetEntity: Log::class)]
    private ?int $log = null;

    /**
     * Many Criteria have many assignments.
     */
    #[ORM\ManyToMany(targetEntity: Assignment::class, mappedBy: 'criteria')]
    private Collection $assignments;

    public function __construct()
    {
        $this->descriptors = new ArrayCollection();
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

    public function getAuthor(): ?Person
    {
        return $this->author;
    }

    public function setAuthor(?Person $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Descriptor[]
     */
    public function getDescriptors(): Collection
    {
        return $this->descriptors;
    }

    public function addDescriptor(Descriptor $descriptor): self
    {
        if (!$this->descriptors->contains($descriptor)) {
            $this->descriptors[] = $descriptor;
            $descriptor->addCriterion($this);
        }

        return $this;
    }

    public function removeDescriptor(Descriptor $descriptor): self
    {
        if ($this->descriptors->contains($descriptor)) {
            $this->descriptors->removeElement($descriptor);
            $descriptor->removeCriterion($this);
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

    public function addAssignment(Assignment $assignment): self
    {
        if (!$this->assignments->contains($assignment)) {
            $this->assignments[] = $assignment;
            $assignment->addCriterion($this);
        }

        return $this;
    }

    public function removeAssignment(Assignment $assignment): self
    {
        if ($this->assignments->contains($assignment)) {
            $this->assignments->removeElement($assignment);
            $assignment->removeCriterion($this);
        }

        return $this;
    }
}
