<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 */
#[ORM\Table(name: 'comment')]
#[ORM\Entity(repositoryClass: \App\Repository\CommentRepository::class)]
class Comment
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
    #[ORM\Column(name: 'text', type: 'text')]
    private $text;

    /**
     * @var string
     */
    #[ORM\Column(name: 'type', type: 'string', length: 100)]
    private $type;

    #[ORM\JoinColumn(name: 'person_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: \Person::class, inversedBy: 'Comment', cascade: ['persist', 'remove'])]
    private $owner;    

    #[ORM\JoinColumn(name: 'submission_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: \Submission::class, inversedBy: 'Comment', cascade: ['persist', 'remove'])]
    private $submission;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'createtime', type: 'datetime')]
    private $createtime;


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
     * Set text
     *
     * @param string $text
     *
     * @return Comment
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Comment
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    public function getCreatetime(): ?\DateTimeInterface
    {
        return $this->createtime;
    }

    public function setCreatetime(\DateTimeInterface $createtime): self
    {
        $this->createtime = $createtime;

        return $this;
    }

    public function getOwner(): ?Person
    {
        return $this->owner;
    }

    public function setOwner(?Person $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getSubmission(): ?Submission
    {
        return $this->submission;
    }

    public function setSubmission(?Submission $submission): self
    {
        $this->submission = $submission;

        return $this;
    }    
    
}

