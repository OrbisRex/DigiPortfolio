<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Log.
 */
#[ORM\Table(name: 'log')]
#[ORM\Entity(repositoryClass: \App\Repository\LogRepository::class)]
class Log
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
    #[ORM\Column(name: 'operation', type: 'string', length: 255)]
    private $operation;

    /**
     * One log have one person.
     */
    #[ORM\OneToOne(targetEntity: Person::class)]
    private $person;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'timestamp', type: 'datetime')]
    private $timestamp;

    /**
     * @var string
     */
    #[ORM\Column(name: 'result', type: 'string', length: 100)]
    private $result;

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
     * Set operation.
     *
     * @param string $operation
     *
     * @return Log
     */
    public function setOperation($operation)
    {
        $this->operation = $operation;

        return $this;
    }

    /**
     * Get operation.
     *
     * @return string
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * Get person.
     */
    public function getPerson(): ?Person
    {
        return $this->person;
    }

    /**
     * Set person.
     *
     * @param Person $person
     */
    public function setPerson($person): self
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Set timestamp.
     *
     * @param \DateTime $timestamp
     *
     * @return Log
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp.
     *
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set result.
     *
     * @param string $result
     *
     * @return Log
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get result.
     *
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }
}
