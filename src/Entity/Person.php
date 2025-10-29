<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use App\Entity\AssignmentPerson;

use App\Repository\PersonRepository;

#[ORM\Table(name: 'person')]
#[ORM\Entity(repositoryClass: PersonRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Person implements UserInterface, PasswordAuthenticatedUserInterface
{
    private const ROLE_USER = 'ROLE_USER';
    private const ROLE_STUDENT = 'ROLE_STUDENT';
    private const ROLE_TEACHER = 'ROLE_TEACHER';
    private const ROLE_ADMIN = 'ROLE_ADMIN';
    
    
    #[ORM\Id]
    #[ORM\Column()]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    /**
     * The hashed password
     */
    #[ORM\Column(type: 'string')]
    private ?string $password = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: 'smallint')]
    private ?int $disabled = null;

    /**
     * Many people can organise many sets (co-leading).
     */
    #[ORM\ManyToMany(targetEntity: Set::class, mappedBy: 'people', cascade: ['persist'])]
    private Collection $sets;

    /**
     * Many people can have many subjects (co-teaching).
     */
    #[ORM\ManyToMany(targetEntity: Subject::class, mappedBy: 'people', cascade: ['persist'])]
    private Collection $subjects;

    /**
     * One person can have many topics (author).
     */
    #[ORM\OneToMany(targetEntity: Topic::class, mappedBy: 'person', cascade: ['persist'])]
    private Collection $topics;

    /**
     * One peron can have multiple files.
     */
    #[ORM\OneToMany(targetEntity: ResourceFile::class, mappedBy: 'owner')]
    private Collection $resourceFiles;

    /**
     * One person can have many criteria (author).
     */
    #[ORM\OneToMany(targetEntity: Criterion::class, mappedBy: 'author')]
    private Collection $criteria;

    /**
     * One person can be part of many assignments (teaher and students).
     */
    #[ORM\OneToMany(targetEntity: AssignmentPerson::class, mappedBy: 'person')]
    private Collection $assignments;

    /**
     * Many persons can create many submissions (sharing).
     */
    #[ORM\ManyToMany(targetEntity: Submission::class, mappedBy: 'people')]
    private Collection $submissions;

    /**
     * One person can have many feedbacks (author).
     */
    #[ORM\OneToMany(targetEntity: Feedback::class, mappedBy: 'author')]
    private Collection $feedbacks;

    /**
     * One person can have many comments (author).
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'author')]
    private Collection $comments;

    /**
     * One Person has One Log.
     */
    #[ORM\OneToOne(targetEntity: Log::class,)]
    private ?int $log = null;

    public function __construct()
    {
        $this->sets = new ArrayCollection();
        $this->subjects = new ArrayCollection();
        $this->topics = new ArrayCollection();
        $this->resourceFiles = new ArrayCollection();
        $this->criteria = new ArrayCollection();
        $this->assignments = new ArrayCollection();
        $this->submissions = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * Return array of user roles.
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        if(empty($roles)) {
            $roles[] = self::ROLE_USER;
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt(): void
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDisabled(): ?int
    {
        return $this->disabled;
    }

    public function setDisabled(?int $disabled): self
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * @return Collection|Set[]
     */
    public function getSets(): Collection
    {
        return $this->sets;
    }

    public function addSet(?Set $set): self
    {
        if (!empty($set) && !$this->sets->contains($set)) {
            $this->sets[] = $set;
            $set->addPerson($this);
        }

        return $this;
    }

    public function removeSet(Set $set): self
    {
        dump($this->sets->contains($set));
        if ($this->sets->contains($set)) {
            dump($set);
            dump($this->sets);
            $this->sets->removeElement($set);
            // Set the owning side to null (unless already changed)
            $set->addPerson(null);
            dump($this->sets);
        }

        return $this;
    }

    /**
     * @return Collection|Subject[]
     */
    public function getSubject(): Collection
    {
        return $this->subjects;
    }

    public function addSubject(Subject $subject): self
    {
        if (!$this->subjects->contains($subject)) {
            $this->subjects[] = $subject;
        }

        return $this;
    }

    public function removeSubject(Subject $subject): self
    {
        if ($this->subjects->contains($subject)) {
            $this->subjects->removeElement($subject);
        }

        return $this;
    }

    /**
     * @return Collection|Topic[]
     */
    public function getTopics(): Collection
    {
        return $this->topics;
    }

    public function addTopic(Topic $topic): self
    {
        if (!$this->topics->contains(${$topic})) {
            $this->topics[] = ${$topic};
        }

        return $this;
    }

    public function removeTopic(Topic $topic): self
    {
        if ($this->topics->contains($topic)) {
            $this->topics->removeElement($topic);
        }

        return $this;
    }

    /**
     * @return Collection|ResourceFile[]
     */
    public function getResourceFiles(): Collection
    {
        return $this->resourceFiles;
    }

    public function addResourceFile(ResourceFile $file): self
    {
        if (!$this->resourceFiles->contains($file)) {
            $this->resourceFiles[] = $file;
        }

        return $this;
    }

    public function removeResourceFile(ResourceFile $file): self
    {
        if ($this->resourceFiles->contains($file)) {
            $this->resourceFiles->removeElement($file);
        }

        return $this;
    }

    /**
     * @return Collection|Criterion[]
     */
    public function getCriteria(): Collection
    {
        return $this->criteria;
    }

    public function addCriterion(Criterion $criterion): self
    {
        if (!$this->criteria->contains($criterion)) {
            $this->criteria[] = $criterion;
        }

        return $this;
    }

    public function removeCriterion(Criterion $criterion): self
    {
        if ($this->criteria->contains($criterion)) {
            $this->criteria->removeElement($criterion);
        }

        return $this;
    }

    /**
     * @return Collection|AssignmentPerson[]
     */
    public function getAssignments(): Collection
    {
        return $this->assignments;
    }

    public function addAssignment(Assignment $assigment): self
    {
        if (!$this->assignments->contains($assigment)) {
            $this->assignments[] = $assigment;
        }

        return $this;
    }

    public function removeAssignment(Assignment $assigment): self
    {
        if ($this->assignments->contains($assigment)) {
            $this->assignments->removeElement($assigment);
        }

        return $this;
    }

    /**
     * @return Collection|Submission[]
     */
    public function getSubmissions(): Collection
    {
        return $this->submissions;
    }

    public function addSubmission(Submission $submission): self
    {
        if (!$this->submissions->contains($submission)) {
            $this->submissions[] = $submission;
        }

        return $this;
    }

    public function removeSubmission(Submission $submission): self
    {
        if ($this->submissions->contains($submission)) {
            $this->submissions->removeElement($submission);
        }

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

    /**
     * @return Collection|Feedback[]
     */
    public function getFeedbacks(): Collection
    {
        return $this->feedbacks;
    }

    public function addFeedback(Feedback $feedbacks): self
    {
        if (!$this->feedbacks->contains($feedbacks)) {
            $this->feedbacks[] = $feedbacks;
        }

        return $this;
    }

    public function removeFeedback(Feedback $feedback): self
    {
        if ($this->feedbacks->contains($feedback)) {
            $this->feedbacks->removeElement($feedback);
        }

        return $this;
    }
}
