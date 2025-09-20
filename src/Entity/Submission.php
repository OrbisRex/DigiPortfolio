<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

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
     * Many submissions can have many files.
     */
    #[ORM\ManyToMany(targetEntity: ResourceFile::class, inversedBy: 'submissions', cascade: ['persist'])]
    private Collection $files;

    /**
     * Many submissions can belong to many people.
     */
    #[ORM\ManyToMany(targetEntity: Person::class, inversedBy: 'submissions', cascade: ['persist'])]
    private Collection $people;

    /**
     * Submissions can belong to one assignment.
     */
    #[ORM\ManyToOne(targetEntity: Assignment::class, inversedBy: 'submissions', cascade: ['persist', 'remove'])]
    private ?Assignment $assignment = null;

    #[ORM\Column(nullable: false)]
    #[ORM\OneToOne(targetEntity: Log::class, cascade: ['persist', 'remove'])]
    private ?int $log = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?DateTimeImmutable $updatetime = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $createtime = null;

    /**
     * Submission can have many feedbacks.
     */    
    #[ORM\OneToMany(targetEntity: Feedback::class, mappedBy: 'submission', cascade: ['persist', 'remove'])]
    private Collection $feedbacks;

    public function __construct()
    {
        $this->people = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->feedbacks = new ArrayCollection();
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
                $file->addSubmission($this);
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

    public function setAssignment(?Assignment $assignment): self
    {
        $this->assignment = $assignment;

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

    public function getUpdatetime(): ?DateTimeImmutable
    {
        return $this->updatetime;
    }

    public function setUpdatetime(DateTimeImmutable $updatetime): self
    {
        $this->updatetime = $updatetime;

        return $this;
    }

    public function getCreatetime(): ?DateTimeImmutable
    {
        return $this->createtime;
    }

    public function setCreatetime(DateTimeImmutable $createtime): self
    {
        $this->createtime = $createtime;

        return $this;
    }

    /**
     * @return Collection|Feedback[]
     */
    public function getFeedback(): Collection
    {
        return $this->feedbacks;
    }

    public function addFeedback(Feedback $feedback): self
    {
        if (!$this->feedbacks->contains($feedback)) {
            $this->feedbacks[] = $feedback;
        }

        return $this;
    }

    public function removeFeedback(Criterion $criterion): self
    {
        if ($this->feedbacks->contains($criterion)) {
            $this->feedbacks->removeElement($criterion);
        }

        return $this;
    }
}
