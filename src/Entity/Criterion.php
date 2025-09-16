<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Criteria
 */
#[ORM\Table(name: 'criterion')]
#[ORM\Entity(repositoryClass: \App\Repository\CriterionRepository::class)]
class Criterion
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private $id;

    /**
     * @var string
     */
    #[ORM\Column(name: 'name', type: 'string', length: 100)]
    private $name;

    /**
     * Criteria have many descriptors.
     * @var Collection
     */
    #[ORM\JoinTable(name: 'criteria_descriptors')]
    #[ORM\ManyToMany(targetEntity: \Criterion::class, inversedBy: 'criteria', cascade: ['persist'])]
    private $descriptors;

    /**
     * One Criterion has One Person.
     */
    #[ORM\JoinColumn(name: 'person_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: \Person::class, inversedBy: 'criterion')]
    private $author;
    
    /**
     * One Criterion has One Log.
     */
    #[ORM\JoinColumn(name: 'log_id', referencedColumnName: 'id')]
    #[ORM\OneToOne(targetEntity: \Log::class)]
    private $log;

    /**
     * Criteria have many assignments.
     * @var Collection
     */
    #[ORM\ManyToMany(targetEntity: \Assignment::class, mappedBy: 'criteria')]
    private $assignments;

    /**
     * Many Criteria have Many submissions.
     * @var Collection
     */
    #[ORM\ManyToMany(targetEntity: \Submission::class, mappedBy: 'criteria')]
    private $submissions;

    public function __construct()
    {
        $this->descriptors = new ArrayCollection();
        $this->assignments = new ArrayCollection();
        $this->submissions = new ArrayCollection();
    }

    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Criterion
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
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
        $this->person = $author;

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
            $descriptor->removeCriteria($this);
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

    /**
     * @return Collection|Submission[]
     */
    public function getSubmissions(): Collection
    {
        return $this->submissions;
    }

    public function addSubmissions(Submission $submission): self
    {
        if (!$this->submissions->contains($submission)) {
            $this->submissions[] = $submission;
            $submission->addSubmission($this);
        }

        return $this;
    }

    public function removeSubmission(Submission $submission): self
    {
        if ($this->submissions->contains($submission)) {
            $this->submissions->removeElement($submission);
            $submission->removeSubmission($this);
        }

        return $this;
    }

    public function addSubmission(Submission $submission): self
    {
        if (!$this->submissions->contains($submission)) {
            $this->submissions[] = $submission;
            $submission->addCriterion($this);
        }

        return $this;
    }
}