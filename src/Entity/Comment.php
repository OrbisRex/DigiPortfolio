<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

use App\Repository\CommentRepository;
use DateTimeImmutable;

/**
 * Comment.
 */
#[ORM\Table(name: 'comment')]
#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\Column()]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    private ?int $id = null;

    #[ORM\Column(type: 'text')]
    private ?string $text = null;

    #[ORM\Column(length: 100)]
    private ?string $type = null;

    /**
     * Many coments belongs to one author.
     */
    #[ORM\JoinColumn(name: 'person_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(inversedBy: 'comments', cascade: ['persist', 'remove'])]
    private ?Person $author = null;

    /**
     * Many coments belongs to one submission.
     */
    #[ORM\JoinColumn(name: 'feedback_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(inversedBy: 'comments', cascade: ['persist', 'remove'])]
    private ?Feedback $feedback = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?DateTimeImmutable $createtime = null;

    /**
     * Get id.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set text.
     */
    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text.
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * Set type.
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    public function getCreatetime(): ?DateTimeImmutable
    {
        return $this->createtime;
    }

    public function setCreatetime(DateTimeInterface $createtime): self
    {
        $this->createtime = $createtime;

        return $this;
    }

    public function getAuthor(): ?Person
    {
        return $this->author;
    }

    public function setAuthor(?Person $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getFeedback(): ?Feedback
    {
        return $this->feedback;
    }

    public function setFeedback(?Feedback $feedback): self
    {
        $this->feedback = $feedback;

        return $this;
    }
}
