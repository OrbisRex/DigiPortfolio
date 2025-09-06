<?php

namespace App\Entity;

use App\Repository\ResourceFileRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass=ResourceFileRepository::class)
 */
class ResourceFile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $size;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $meta = [];

    /**
     * @ORM\ManyToOne(targetEntity=Person::class, inversedBy="resourceFiles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\OneToOne(targetEntity=Log::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $log;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatetime;

    /**
     * 
     * @var File|null
     */
    private $file;

    /**
     * @ORM\ManyToOne(targetEntity=Submission::class, inversedBy="file")
     */
    private $submission;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getMeta(): ?array
    {
        return $this->meta;
    }

    public function setMeta(?array $meta): self
    {
        $this->meta = $meta;

        return $this;
    }

    public function getOwner(): ?Person
    {
        return $this->owner;
    }

    public function setOwner(?Person $owner): self
    {
        $this->owner = $owner;

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

    public function getFile(): ?File
    {
        return $this->file;
    }
   
    public function setFile(?File $file = null): void
    {
        $this->file = $file;

        if (null !== $file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatetime = new \DateTimeImmutable();
        }
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

    public function getSubmission(): ?Submission
    {
        return $this->submission;
    }

    public function setSubmission(?Submission $submission): self
    {
        $this->submission = $submission;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }
}
