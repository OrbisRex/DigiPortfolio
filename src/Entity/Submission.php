<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\Assignment;
use App\Entity\ResourceFile;

use App\Repository\SubmissionRepository;

#[ORM\Entity(repositoryClass: SubmissionRepository::class)]
class Submission
{
    #[ORM\Id]
    #[ORM\Column()]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $note = null;

    #[ORM\Column()]
    private int $version = 0;

    #[ORM\Column(length: 1024)]
    private ?string $link = null;

    #[ORM\Column(type: 'text')]
    private ?string $text = null;

    /**
     * Many files can be in many submissions.
     *
     * @var Collection
     */
    #[ORM\ManyToMany(targetEntity: ResourceFile::class, inversedBy: 'submissions', cascade: ['persist'])]
    private Collection $files;

    /**
     * Many people can be in many submissions.
     *
     * @var Collection
     */
    #[ORM\ManyToMany(targetEntity: Person::class, inversedBy: 'submissions', cascade: ['persist'])]
    private Collection $people;

    /**
     * One assignment can have many submissions.
     */
    #[ORM\ManyToOne(targetEntity: Assignment::class, inversedBy: 'submissions', cascade: ['persist', 'remove'])]
    private ?Assignment $assignment = null;

    /**
     * @var Collection
     */
    #[ORM\ManyToMany(targetEntity: Criterion::class, inversedBy: 'submissions', cascade: ['persist'])]
    private Collection $criteria;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\OneToOne(targetEntity: Log::class, cascade: ['persist', 'remove'])]
    private ?int $log = null;

    #[ORM\Column()]
    private ?DateTimeInterface $updatetime = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeInterface $createtime = null;

    #[ORM\OneToOne(mappedBy: 'submission', cascade: ['persist', 'remove'])]
    private ?Feedback $feedback = null;

    public function __construct()
    {
        $this->people = new ArrayCollection();
        $this->files = new ArrayCollection();
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
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(ResourceFile $file): self
    {
        if (!$this->files->contains($file)) {
            $this->files[] = $file;
        }

        return $this;
    }

    public function removeFile(ResourceFile $file): self
    {
        if ($this->files->contains($file)) {
            $this->files->removeElement($file);
            // set the owning side to null (unless already changed) NOTE: Is it better then just remove???
            if ($file->getSubmission($this) === $this) {
                $file->addSubmission(null);
            }
        }

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
            $person->addSubmission($this);
        }

        return $this;
    }

    public function removePerson(Person $person): self
    {
        if ($this->people->contains($person)) {
            $this->people->removeElement($person);
            $person->removeSubmission($this);
        }

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

    public function getUpdatetime(): ?DateTimeInterface
    {
        return $this->updatetime;
    }

    public function setUpdatetime(DateTimeInterface $updatetime): self
    {
        $this->updatetime = $updatetime;

        return $this;
    }

    public function getCreatetime(): ?DateTimeInterface
    {
        return $this->createtime;
    }

    public function setCreatetime(DateTimeInterface $createtime): self
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
