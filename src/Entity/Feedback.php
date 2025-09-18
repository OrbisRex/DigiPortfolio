<?php

namespace App\Entity;

use DateTime;
use App\Repository\FeedbackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FeedbackRepository::class)]
class Feedback
{
    #[ORM\Id]
    #[ORM\Column()]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    private ?int $id = null;

    #[ORM\Column(type: 'text')]
    private ?string $note = null;

    #[ORM\JoinColumn(name: 'person_id', referencedColumnName: 'id', nullable: false)]
    #[ORM\ManyToOne(inversedBy: 'owner', cascade: ['persist', 'remove'])]
    private ?Person $owner = null;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\OneToOne(targetEntity: Submission::class, inversedBy: 'feedback', cascade: ['persist', 'remove'])]
    private $submission;

    #[ORM\ManyToMany(targetEntity: Descriptor::class, inversedBy: 'descriptors', cascade: ['persist'])]
    private Collection $descriptors;

    #[ORM\OneToOne(targetEntity: Log::class, cascade: ['persist', 'remove'])]
    private ?int $log = null;

    #[ORM\Column()]
    private ?int $version = null;

    #[ORM\Column()]
    private ?DateTime $createtime = null;

    #[ORM\Column()]
    private ?DateTime $updatetime = null;

    public function __construct()
    {
        $this->descriptors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOwner(): ?Person
    {
        return $this->owner;
    }

    public function setOwner(Person $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getSubmission(): ?Submission
    {
        return $this->submission;
    }

    public function setSubmission(Submission $submission): self
    {
        $this->submission = $submission;

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
        }

        return $this;
    }

    public function removeDescriptor(Descriptor $descriptor): self
    {
        if ($this->descriptors->contains($descriptor)) {
            $this->descriptors->removeElement($descriptor);
        }

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

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(int $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getCreatetime(): ?DateTimeInterface
    {
        return $this->createtime;
    }

    public function setCreatetime(?DateTimeInterface $createtime): self
    {
        $this->createtime = $createtime;

        return $this;
    }

    public function getUpdatetime(): ?DateTimeInterface
    {
        return $this->updatetime;
    }

    public function setUpdatetime(?DateTimeInterface $updatetime): self
    {
        $this->updatetime = $updatetime;

        return $this;
    }
}
