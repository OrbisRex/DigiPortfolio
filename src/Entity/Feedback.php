<?php

namespace App\Entity;

use App\Repository\FeedbackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FeedbackRepository::class)
 */
class Feedback
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity=Person::class, inversedBy="owner", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id", nullable=false)
     */
    private $owner;

    /**
     * @ORM\OneToOne(targetEntity=Submission::class, inversedBy="feedback", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $submission;

    /**
     * @ORM\ManyToMany(targetEntity=Descriptor::class)
     */
    private $descriptors;

    /**
     * @ORM\OneToOne(targetEntity=Log::class, cascade={"persist", "remove"})
     */
    private $log;

    /**
     * @ORM\Column(type="integer")
     */
    private $version;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createtime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatetime;

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

    public function getCreatetime(): ?\DateTimeInterface
    {
        return $this->createtime;
    }

    public function setCreatetime(?\DateTimeInterface $createtime): self
    {
        $this->createtime = $createtime;

        return $this;
    }

    public function getUpdatetime(): ?\DateTimeInterface
    {
        return $this->updatetime;
    }

    public function setUpdatetime(?\DateTimeInterface $updatetime): self
    {
        $this->updatetime = $updatetime;

        return $this;
    }
}
