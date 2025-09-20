<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

use App\Repository\LogRepository;

/**
 * Log.
 */
#[ORM\Table(name: 'log')]
#[ORM\Entity(repositoryClass: LogRepository::class)]
class Log
{
    #[ORM\Id]
    #[ORM\Column()]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $operation = null;

    /**
     * One log have one person.
     */
    #[ORM\OneToOne()]
    private ?Person $person = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?DateTimeImmutable $timestamp = null;

    #[ORM\Column(length: 100)]
    private ?string $result = null;

    /**
     * Get id.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set operation.
     */
    public function setOperation(string $operation): self
    {
        $this->operation = $operation;

        return $this;
    }

    /**
     * Get operation.
     */
    public function getOperation(): ?string
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
     */
    public function setPerson($person): self
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Set timestamp.
     */
    public function setTimestamp(DateTimeImmutable $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp.
     */
    public function getTimestamp(): ?DateTimeImmutable
    {
        return $this->timestamp;
    }

    /**
     * Set result.
     */
    public function setResult(string $result): self
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get result.
     */
    public function getResult(): ?string
    {
        return $this->result;
    }
}
