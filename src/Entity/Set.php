<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\Assignment;

use App\Repository\SetRepository;

/**
 * Set.
 */
#[ORM\Table(name: 'set')]
#[ORM\Entity(repositoryClass: SetRepository::class)]
class Set
{
    /**
     * @var int
     */
    #[ORM\Id]
    #[ORM\Column()]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    private ?int $id = null;

    /**
     * @var string
     */
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var string
     */
    #[ORM\Column(length: 255)]
    private ?string $type = null;

    /**
     * Many people can be in many sets.
     *
     * @var Collection
     */
    #[ORM\ManyToMany(targetEntity: \Person::class, inversedBy: 'sets', cascade: ['persist'])]
    private Collection $people;

    /**
     * One set has many assignments.
     */
    #[ORM\OneToMany(targetEntity: Assignment::class, mappedBy: 'set')]
    private Collection $assignments;

    /**
     * One Topic has One Log.
     */
    #[ORM\JoinColumn(name: 'log_id', referencedColumnName: 'id')]
    #[ORM\OneToOne(targetEntity: Log::class, cascade: ['persist', 'remove'])]
    private ?int $log = null;

    public function __construct()
    {
        $this->people = new ArrayCollection();
        $this->assignments = new ArrayCollection();
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
     * @return Set
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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
            $person->addSet($this);
        }

        return $this;
    }

    public function removePerson(Person $person): self
    {
        if ($this->people->contains($person)) {
            $this->people->removeElement($person);
            $person->removeSet($this);
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
}
