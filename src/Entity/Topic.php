<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Topic
 */
#[ORM\Table(name: 'topic')]
#[ORM\Entity(repositoryClass: \App\Repository\TopicRepository::class)]
class Topic
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private $id;

    /**
     * @var string
     */
    #[ORM\Column(name: 'name', type: 'string', length: 255)]
    private $name;

    /**
     * @var string
     */
    #[ORM\Column(name: 'description', type: 'string', length: 255, nullable: true)]
    private $description;
    
    /**
     * One topic has many assignments.
     */
    #[ORM\OneToMany(targetEntity: \Assignment::class, mappedBy: 'topic')]
    private $assignments;

    /**
     * Many topics has one person.
     */
    #[ORM\ManyToOne(targetEntity: \Person::class, inversedBy: 'topics')]
    private $person;

    /**
     * One Topic has One Log.
     */
    #[ORM\JoinColumn(name: 'log_id', referencedColumnName: 'id')]
    #[ORM\OneToOne(targetEntity: \Log::class)]
    private $log;
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Topic
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Topic
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): self
    {
        $this->person = $person;

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

