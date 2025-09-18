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
     * @var int
     */
    #[ORM\Id]
    #[ORM\Column()]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    private ?int $id = null;

    /**
     * Many people can have one assignment.
     *
     * @var Person
     */
    #[ORM\ManyToOne(inversedBy: 'assignments')]
    private ?Person $person = null;

    /**
     * Many assignments have one person.
     *
     * @var Assignment
     */
    #[ORM\ManyToOne(inversedBy: 'people')]
    private ?Assignment $assignment = null;

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
     * Set assignment.
     *
     * @param int $assignment
     *
     * @return AssignmentPerson
     */
    public function setAssignment($assignment)
    {
        $this->assignment = $assignment;

        return $this;
    }

    /**
     * Get assignment.
     *
     * @return Assignment
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
