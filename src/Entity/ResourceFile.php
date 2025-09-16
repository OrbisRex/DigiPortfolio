<?php

namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

use App\Entity\Person;

use App\Repository\ResourceFileRepository;

#[ORM\Entity(repositoryClass: ResourceFileRepository::class)]
class ResourceFile
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column()]
    private ?int $size = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private array $meta = [];

    /**
     * One file can have one owner.
     */
    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(inversedBy: 'resourceFiles')]
    private ?Person $owner = null;

    /**
     * Many file can be in many submissions.
     *
     * @var Collection
     */
    #[ORM\ManyToMany(targetEntity: \Submission::class, mappedBy: 'files', cascade: ['persist'])]
    private Collection $submissions;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\JoinColumn(nullable: true)]
    #[ORM\OneToOne(targetEntity: Log::class, cascade: ['persist', 'remove'])]
    private ?int $log = null;

    #[ORM\Column(type: 'datetime')]
    private ?DateTimeInterface $updatetime = null;

    /**
     * @var File|null
     */
    private $file;

    public function __construct()
    {
        $this->submissions = new ArrayCollection();
    }

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

    /**
     * @return Collection|Submission[]
     */
    public function getSubmissions(): Collection
    {
        return $this->submissions;
    }

    public function getSubmission(Submission $submission): self
    {
        return $this->submissions[$submission];
    }

    public function addSubmission(Submission $submission): self
    {
        if (!$this->submissions->contains($submission)) {
            $this->submissions[] = $submission;
        }

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
            $this->updatetime = new DateTimeImmutable();
        }
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
}
