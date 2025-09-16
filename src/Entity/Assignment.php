<?php

namespace App\Entity;

use DateTime;
use App\Repository\AssignmentRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\Subject;
use App\Entity\Topic;
use App\Entity\AssignmentPerson;

/**
 * Assignment.
 */
#[ORM\Table(name: 'assignment')]
#[ORM\Entity(repositoryClass: AssignmentRepository::class)]
class Assignment
{
    /**
     * @var int
     */
    #[ORM\Id]
    #[ORM\Column()]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    /**
     * @var string
     */
    #[ORM\Column(length: 255, nullable: false)]
    private ?string $name = null;

    /**
     * @var string
     */
    #[ORM\Column(length: 100)]
    private ?string $state = null;

    /**
     * Many Assignments has one Subject.
     */
    #[ORM\JoinColumn(name: 'subject_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(inversedBy: 'subjects', cascade: ['persist', 'remove'])]
    private ?Subject $subject = null;

    /**
     * Many Assignments has one Topic.
     */
    #[ORM\JoinColumn(name: 'topic_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(inversedBy: 'topics', cascade: ['persist', 'remove'])]
    private ?Topic $topic = null;

    /**
     * Many Assignments has one set.
     */
    #[ORM\JoinColumn(name: 'set_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(inversedBy: 'assignments', cascade: ['persist', 'remove'])]
    private ?Set $set = null;

    /**
     * One assignment can have many people.
     *
     * @var Collection
     */
    #[ORM\OneToMany(targetEntity: AssignmentPerson::class, mappedBy: 'person')]
    private Collection $people;

    /**
     * @var string
     */
    #[ORM\Column(length: 255)]
    private ?string $note = null;

    /**
     * @var DateTimeInterface
     */
    #[ORM\Column()]
    private ?DateTimeInterface $updatetime = null;

    /**
     * Assignment has many criteria.
     *
     * @var Collection
     */
    #[ORM\ManyToMany(targetEntity: \Criterion::class, inversedBy: 'assignments', cascade: ['persist'])]
    private Collection $criteria;

    /**
     * One assignment can have many submissions.
     *
     * @var Collection
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
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Assignment
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set state.
     *
     * @param string $state
     *
     * @return Assignment
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state.
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set subject.
     *
     * @param string $subject
     *
     * @return Assignment
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject.
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set topic.
     *
     * @param string $topic
     *
     * @return Assignment
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * Get topic.
     *
     * @return string
     */
    public function getTopic()
    {
        return $this->topic;
    }

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
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set note.
     *
     * @param string $note
     *
     * @return Assignment
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get updatetime.
     *
     */
    public function getUpdatetime(): ?DateTimeInterface
    {
        return $this->updatetime;
    }

    /**
     * Set updatetime.
     *
     * @param string $updatetime
     */
    public function setUpdatetime(DateTimeInterface $updatetime): self
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
        $this->submission = $submission;

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
