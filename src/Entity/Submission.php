<?php

namespace App\Entity;

use App\Repository\SubmissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SubmissionRepository::class)
 */
class Submission
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $note;

    /**
     * @ORM\Column(type="integer")
     */
    private $version;

    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     */
    private $link;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $text;

    /**
     * @ORM\OneToMany(targetEntity=ResourceFile::class, mappedBy="submission")
     */
    private $file;

    /**
     * @ORM\ManyToOne(targetEntity=Person::class, inversedBy="owner", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id", nullable=false)
     */
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity=Assignment::class, inversedBy="submission", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="assignment_id", referencedColumnName="id", nullable=false)
     */
    private $assignment;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="Criterion", inversedBy="submission", cascade={"persist"})
     */
    private $criteria;

    /**
     * @ORM\OneToOne(targetEntity=Log::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $log;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatetime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createtime;

    /**
     * @ORM\OneToOne(targetEntity=Feedback::class, mappedBy="submission", cascade={"persist", "remove"})
     */
    private $feedback;

    public function __construct()
    {
        $this->file = new ArrayCollection();
        $this->criteria = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(int $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return Collection|ResourceFile[]
     */
    public function getFile(): Collection
    {
        return $this->file;
    }

    public function addFile(ResourceFile $file): self
    {
        if (!$this->file->contains($file)) {
            $this->file[] = $file;
            $file->setSubmission($this);
        }

        return $this;
    }

    public function removeFile(ResourceFile $file): self
    {
        if ($this->file->contains($file)) {
            $this->file->removeElement($file);
            // set the owning side to null (unless already changed)
            if ($file->getSubmission() === $this) {
                $file->setSubmission(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?Person
    {
        return $this->owner;
    }

    public function setOwner(Person $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getAssignment(): ?Assignment
    {
        return $this->assignment;
    }

    public function setAssignment(Assignment $assignment): self
    {
        $this->assignment = $assignment;

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

    public function getLog(): ?Log
    {
        return $this->log;
    }

    public function setLog(Log $log): self
    {
        $this->log = $log;

        return $this;
    }

    public function getUpdatetime(): ?\DateTimeInterface
    {
        return $this->updatetime;
    }

    public function setUpdatetime(\DateTimeInterface $updatetime): self
    {
        $this->updatetime = $updatetime;

        return $this;
    }

    public function getCreatetime(): ?\DateTimeInterface
    {
        return $this->createtime;
    }

    public function setCreatetime(\DateTimeInterface $createtime): self
    {
        $this->createtime = $createtime;

        return $this;
    }

    public function getFeedback(): ?Feedback
    {
        return $this->feedback;
    }

    public function setFeedback(Feedback $feedback): self
    {
        $this->feedback = $feedback;

        // set the owning side of the relation if necessary
        if ($feedback->getSubmission() !== $this) {
            $feedback->setSubmission($this);
        }

        return $this;
    }
}
