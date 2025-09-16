<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use DateTimeInterface;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Comment.
 */
#[ORM\Table(name: 'comment')]
#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
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
    #[ORM\Column(type: 'text')]
    private ?string $text = null;

    /**
     * @var string
     */
    #[ORM\Column(length: 100)]
    private ?string $type = null;

    #[ORM\JoinColumn(name: 'person_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(inversedBy: 'Comment', cascade: ['persist', 'remove'])]
    private ?Person $owner = null;

    #[ORM\JoinColumn(name: 'submission_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(inversedBy: 'Comment', cascade: ['persist', 'remove'])]
    private ?Submission $submission = null;

    /**
     * @var DateTimeInterface
     */
    #[ORM\Column()]
    private ?DateTimeInterface $createtime = null;

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
     * Set text.
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
     * Get text.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set type.
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
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    public function getCreatetime(): ?DateTimeInterface
    {
        return $this->createtime;
    }

    public function setCreatetime(DateTimeInterface $createtime): self
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
