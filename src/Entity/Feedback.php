<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

use App\Repository\FeedbackRepository;

#[ORM\Entity(repositoryClass: FeedbackRepository::class)]
class Feedback
{
    #[ORM\Id]
    #[ORM\Column()]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    private ?int $id = null;

    #[ORM\Column(type: 'text')]
    private ?string $note = null;

    /**
     * Feedback belongs to one author.
     */
    #[ORM\JoinColumn(name: 'person_id', referencedColumnName: 'id', nullable: false)]
    #[ORM\ManyToOne(inversedBy: 'feedbacks', cascade: ['persist', 'remove'])]
    private ?Person $author = null;

    /**
     * One Feedback belongs to one submission.
     */
    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: Submission::class, inversedBy: 'feedbacks', cascade: ['persist', 'remove'])]
    private $submission;

    /**
     * Feedbacks have many comments.
     */
    #[ORM\JoinColumn(name: 'comment_id', referencedColumnName: 'id')]
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy:'feedback', cascade: ['persist'])]
    private Collection $comments;

    /**
     * One Feedback has One Log.
     */
    #[ORM\OneToOne(targetEntity: Log::class, cascade: ['persist', 'remove'])]
    private ?int $log = null;

    #[ORM\Column()]
    private ?int $version = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?DateTimeImmutable $createtime = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?DateTimeImmutable $updatetime = null;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getAuthor(): ?Person
    {
        return $this->author;
    }

    public function setAuthor(Person $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getSubmission(): ?Submission
    {
        return $this->submission;
    }

    public function setSubmission(Submission $submission): self
    {
        $this->submission = $submission;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
        }

        return $this;
    }

    public function getLog(): ?Log
    {
        return $this->log;
    }

    public function setLog(?Log $log): self
    {
        $this->log = $log;

        return $this;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(int $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getCreatetime(): ?DateTimeImmutable
    {
        return $this->createtime;
    }

    public function setCreatetime(?DateTimeImmutable $createtime): self
    {
        $this->createtime = $createtime;

        return $this;
    }

    public function getUpdatetime(): ?DateTimeImmutable
    {
        return $this->updatetime;
    }

    public function setUpdatetime(?DateTimeImmutable $updatetime): self
    {
        $this->updatetime = $updatetime;

        return $this;
    }
}
