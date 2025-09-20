<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Assignment;

use App\Repository\AssignmentPersonRepository;

/**
 * AssignmentPerson.
 */
#[ORM\Table(name: 'assignment_person')]
#[ORM\Entity(repositoryClass: AssignmentPersonRepository::class)]
class AssignmentPerson
{
    /**
     * Id
     */
    #[ORM\Id]
    #[ORM\Column()]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    private ?int $id = null;

    /**
     * Many people can have one assignment.
     */
    #[ORM\ManyToOne(inversedBy: 'assignments')]
    private ?Person $person = null;

    /**
     * Many assignments have one person.
     */
    #[ORM\ManyToOne(inversedBy: 'people')]
    private ?Assignment $assignment = null;

    /**
     * Get id.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set assignment.
     */
    public function setAssignment(Assignment $assignment): self
    {
        $this->assignment = $assignment;

        return $this;
    }

    /**
     * Get assignment.
     */
    public function getAssignment()
    {
        return $this->assignment;
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
}
