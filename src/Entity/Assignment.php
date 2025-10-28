<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

use App\Entity\Subject;
use App\Entity\Topic;
use App\Entity\AssignmentPerson;
use App\Entity\Criterion;

use App\Repository\AssignmentRepository;

/**
 * Assignment.
 */
#[ORM\Table(name: 'assignment')]
#[ORM\Entity(repositoryClass: AssignmentRepository::class)]
class Assignment
{
    #[ORM\Id]
    #[ORM\Column()]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $name = null;

    #[ORM\Column(length: 100)]
    private ?string $state = null;

    /**
     * Many Assignments has one Subject.
     */
    #[ORM\JoinColumn(name: 'subject_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(inversedBy: 'assignments', cascade: ['persist', 'remove'])]
    private ?Subject $subject = null;

    /**
     * Many Assignments has one Topic.
     */
    #[ORM\JoinColumn(name: 'topic_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(inversedBy: 'assignments', cascade: ['persist', 'remove'])]
    private ?Topic $topic = null;

    /**
     * Many Assignments has one set.
     */
    #[ORM\JoinColumn(name: 'set_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(inversedBy: 'assignments', cascade: ['persist', 'remove'])]
    private ?Set $set = null;

    /**
     * One assignment can have many people.
     */
    #[ORM\OneToMany(targetEntity: AssignmentPerson::class, mappedBy: 'assignment')]
    private Collection $people;

    #[ORM\Column(length: 255)]
    private ?string $note = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?DateTimeImmutable $updatetime = null;

    /**
     * Assignment has many criteria.
     */
    #[ORM\ManyToMany(targetEntity: Criterion::class, inversedBy: 'assignments', cascade: ['persist'])]
    private Collection $criteria;

    /**
     * One assignment can have many submissions.
     */
    #[ORM\OneToMany(targetEntity: Submission::class, mappedBy: 'assignment', cascade: ['persist', 'remove'])]
    private Collection $submissions;

    public function __construct()
    {
        $this->people = new ArrayCollection();
        $this->criteria = new ArrayCollection();
        $this->submissions = new ArrayCollection();
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
     * Set state.
     */
    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state.
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * Set subject.
     */
    public function setSubject(?Subject $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject.
     */
    public function getSubject(): ?Subject
    {
        return $this->subject;
    }

    /**
     * Set topic.
     */
    public function setTopic(?Topic $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * Get topic.
     */
    public function getTopic(): ?Topic
    {
        return $this->topic;
    }

    /**
     * Get set.
     */
    public function getSet(): ?Set
    {
        return $this->set;
    }

    public function setSet(?Set $set): self
    {
        $this->set = $set;

        return $this;
    }

    /**
     * @return Collection|AssignmentPerson[]
     */
    public function getPeople(): Collection
    {
        return $this->people;
    }

    public function addPerson(Person $person): self
    {
        if (!$this->people->contains($person)) {
            $this->people[] = $person;
            $person->addAssignment($this);
        }

        return $this;
    }

    public function removePerson(Person $person): self
    {
        if ($this->people->contains($person)) {
            $this->people->removeElement($person);
            $person->removeAssignment($this);
        }

        return $this;
    }

    /**
     * Get note.
     */
    public function getNote(): ?string
    {
        return $this->note;
    }

    /**
     * Set note.
     */
    public function setNote(string $note): self
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get updatetime.
     */
    public function getUpdatetime(): ?DateTimeImmutable
    {
        return $this->updatetime;
    }

    /**
     * Set updatetime.
     */
    public function setUpdatetime(DateTimeImmutable $updatetime): self
    {
        $this->updatetime = $updatetime;

        return $this;
    }

    /**
     * @return Collection|Criterion[]
     */
    public function getCriteria(): Collection
    {
        return $this->criteria;
    }

    public function addCriterion(Criterion $criterion): self
    {
        if (!$this->criteria->contains($criterion)) {
            $this->criteria[] = $criterion;
        }

        return $this;
    }

    public function removeCriterion(Criterion $criterion): self
    {
        if ($this->criteria->contains($criterion)) {
            $this->criteria->removeElement($criterion);
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

    public function setSubmissions(Submission $submission): self
    {
        $this->submissions[] = $submission;

        // set the owning side of the relation if necessary
        if ($submission->getAssignment() !== $this) {
            $submission->setAssignment($this);
        }

        return $this;
    }

    public function addSubmission(Submission $submission): self
    {
        if (!$this->submissions->contains($submission)) {
            $this->submissions[] = $submission;
            $submission->setAssignment($this);
        }

        return $this;
    }

    public function removeSubmission(Submission $submission): self
    {
        if ($this->submissions->contains($submission)) {
            $this->submissions->removeElement($submission);
            // set the owning side to null (unless already changed)
            if ($submission->getAssignment() === $this) {
                $submission->setAssignment(null);
            }
        }

        return $this;
    }
}
