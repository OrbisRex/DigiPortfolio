<?php

namespace App\Entity;

use App\Repository\LogRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Log.
 */
#[ORM\Table(name: 'log')]
#[ORM\Entity(repositoryClass: LogRepository::class)]
class Log
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
    #[ORM\Column(length: 255)]
    private ?string $operation = null;

    /**
     * One log have one person.
     */
    #[ORM\OneToOne()]
    private ?Person $person = null;

    /**
     * @var DateTimeInterface
     */
    #[ORM\Column()]
    private ?DateTimeInterface $timestamp = null;

    /**
     * @var string
     */
    #[ORM\Column(length: 100)]
    private ?string $result = null;

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
     */
    public function setTimestamp(DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp.
     *
     * @return DateTime
     */
    public function getTimestamp(): ?DateTimeInterface
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
