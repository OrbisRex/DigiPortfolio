<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\Person;

use App\Repository\DescriptorRepository;

/**
 * Descriptor.
 */
#[ORM\Table(name: 'descriptor')]
#[ORM\Entity(repositoryClass: DescriptorRepository::class)]
class Descriptor
{
    #[ORM\Id]
    #[ORM\Column()]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 100)]
    private ?string $type = null;

    #[ORM\Column()]
    private ?int $weight = null;

    /**
     * Many Descriptors have many criteria.
     */
    #[ORM\ManyToMany(targetEntity: Criterion::class, mappedBy: 'descriptors')]
    private Collection $criteria;

    /**
     * Descriptor belongs to one author.
     */
    #[ORM\JoinColumn(name: 'person_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne()]
    private ?Person $author = null;

    /**
     * Descriptor has one comment.
     */
    #[ORM\JoinColumn(name: 'comment_id', referencedColumnName: 'id')]
    #[ORM\OneToOne(targetEntity: Comment::class)]
    private ?int $comment= null;

    /**
     * One Descriptor has One Log.
     */
    #[ORM\JoinColumn(name: 'log_id', referencedColumnName: 'id')]
    #[ORM\OneToOne(targetEntity: Log::class)]
    private ?int $log= null;

    public function __construct()
    {
        $this->criteria = new ArrayCollection();
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
     * Set description.
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set type.
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Set weight.
     */
    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight.
     */
    public function getWeight(): ?int
    {
        return $this->weight;
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

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function setComment(?Comment $comment): self
    {
        $this->comment = $comment;

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

    public function getAuthor(): ?Person
    {
        return $this->author;
    }

    public function setAuthor(?Person $author): self
    {
        $this->author = $author;

        return $this;
    }
}
