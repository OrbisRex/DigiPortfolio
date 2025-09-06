<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AssignmentPerson
 *
 * @ORM\Table(name="assignment_person")
 * @ORM\Entity(repositoryClass="App\Repository\AssignmentPersonRepository")
 */
class AssignmentPerson
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Person")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $person;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Assignment")
     * @ORM\JoinColumn(name="assignment_id", referencedColumnName="id")
     */
    private $assignment;


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
     * Set assignment
     *
     * @param integer $assignment
     *
     * @return AssignmentUser
     */
    public function setAssignment($assignment)
    {
        $this->assignment = $assignment;

        return $this;
    }

    /**
     * Get assignment
     *
     * @return int
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

