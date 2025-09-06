<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Assignment
 *
 * @ORM\Table(name="assignment")
 * @ORM\Entity(repositoryClass="App\Repository\AssignmentRepository")
 */
class Assignment
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=100, nullable=true)
     */
    private $state;
    
    /**
     * Many Assignments has one Subject.
     * @ORM\ManyToOne(targetEntity="Subject", inversedBy="Assignment", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="subject_id", referencedColumnName="id")
     */
    private $subject;

    /**
     * Many Assignments has one Topic.
     * @ORM\ManyToOne(targetEntity="Topic", inversedBy="Assignment", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="topic_id", referencedColumnName="id")
     */
    private $topic;

    /**
     * Many Assignments has one set.
     * @ORM\ManyToOne(targetEntity="Set", inversedBy="Assignment", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="set_id", referencedColumnName="id")
     */
    private $set;

    /**
     * Many Assignments has one Person-Teacher.
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="Assignment", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="teacher_id", referencedColumnName="id")
     */
    private $teacher;

    /**
     * @var string
     * @ORM\Column(name="note", type="string", length=255, nullable=true)
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="Assignment", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $person;    

    /**
     * @var \DateTime
     * @ORM\Column(name="updatetime", type="datetime")
     */
    private $updatetime;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="Criterion", inversedBy="assignments", cascade={"persist"})
     */
    private $criteria;

    /**
     * @ORM\OneToMany(targetEntity=Submission::class, mappedBy="assignment", cascade={"persist", "remove"})
     */
    private $submissions;

    public function __construct()
    {
        $this->criteria = new ArrayCollection();
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
     * @return Assignment
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

    /**
     * Set state
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
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }
    
    /**
     * Set subject
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
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }    

    /**
     * Set topic
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
     * Get topic
     *
     * @return string
     */
    public function getTopic()
    {
        return $this->topic;
    }    

    /**
     * Set teacher
     *
     * @param string $teacher
     *
     * @return Assignment
     */
    public function setTeacher($teacher)
    {
        $this->teacher = $teacher;

        return $this;
    } 
    
    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }    
    
    /**
     * Set note
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
     * Set updatetime
     *
     * @param string $updatetime
     *
     * @return Assignment
     */
    public function setUpdatetime($updatetime)
    {
        $this->updatetime = $updatetime;

        return $this;
    }    
    
    /**
     * Get updatetime
     *
     * @return DateTime
     */
    public function getUpdatetime()
    {
        return $this->updatetime;
    }

    public function getTeacher(): ?Person
    {
        return $this->teacher;
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

