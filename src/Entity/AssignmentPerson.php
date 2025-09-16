<?php

namespace App\Entity;

use App\Repository\AssignmentPersonRepository;
use Assignment;
use Doctrine\ORM\Mapping as ORM;

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
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private $id;

    /**
     * Many people can have one assignment.
     *
     * @var Person
     */
    #[ORM\ManyToOne(targetEntity: \Person::class, inversedBy: 'assignments')]
    private $person;

    /**
     * Many assignments have one person.
     *
     * @var Assignment
     */
    #[ORM\ManyToOne(targetEntity: Assignment::class, inversedBy: 'people')]
    private $assignment;

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
