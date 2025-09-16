<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\Assignment;

use App\Repository\SubjectRepository;

/**
 * Subject.
 */
#[ORM\Table(name: 'subject')]
#[ORM\Entity(repositoryClass: SubjectRepository::class)]
class Subject
{
    /**
     * @var int
     */
    #[ORM\Id]
    #[ORM\Column()]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    /**
     * @var string
     */
    #[ORM\Column(length: 100, unique: true)]
    private ?string $name = null;

    /**
     * Many people can have many subjects.
     *
     * @var Collection
     */
    #[ORM\ManyToMany(targetEntity: \Person::class, inversedBy: 'subjects', cascade: ['persist'])]
    private Collection $people;

    /**
     * One subect has many assignments.
     */
    #[ORM\OneToMany(targetEntity: Assignment::class, mappedBy: 'subject')]
    private Collection $assignments;

    /**
     * One Subject has One Log.
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
     * @return Subject
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

    public function getLog(): ?Log
    {
        return $this->log;
    }

    public function setLog(?Log $log): self
    {
        $this->log = $log;

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
            $person->addSubject($this);
        }

        return $this;
    }

    public function removePerson(Person $person): self
    {
        if ($this->people->contains($person)) {
            $this->people->removeElement($person);
            $person->removeSubject($this);
        }

        return $this;
    }
}
