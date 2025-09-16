<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Table(name: 'person')]
#[ORM\Entity(repositoryClass: \App\Repository\PersonRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Person implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', length: 150, nullable: true)]
    private $name;

    #[ORM\Column(type: 'smallint', nullable: true)]
    private $disabled;

    /**
     * Many people can organise many sets (co-leading).
     *
     * @var Collection
     */
    #[ORM\JoinTable(name: 'person_set')]
    #[ORM\ManyToMany(targetEntity: \Set::class, mappedBy: 'people', cascade: ['persist'])]
    private $sets;

    /**
     * Many people can have many subjects (co-teaching).
     *
     * @var Collection
     */
    #[ORM\ManyToMany(targetEntity: \Subject::class, mappedBy: 'people')]
    private $subjects;

    /**
     * One person can have many topics (author).
     *
     * @var Collection
     */
    #[ORM\OneToMany(targetEntity: \Topic::class, mappedBy: 'person')]
    private $topics;

    /**
     * One peron can have multiple files.
     *
     * @var Collection
     */
    #[ORM\OneToMany(targetEntity: \ResourceFile::class, mappedBy: 'owner')]
    private $resourceFiles;

    /**
     * One person can be part of many assignments (teaher and students).
     *
     * @var Collection
     */
    #[ORM\OneToMany(targetEntity: \AssignmentPerson::class, mappedBy: 'assignment')]
    private $assignments;

    /**
     * Many persons can create many submissions (sharing).
     *
     * @var Collection
     */
    #[ORM\ManyToMany(targetEntity: \Submission::class, mappedBy: 'people')]
    private $submissions;

    /**
     * One Person has One Log.
     */
    #[ORM\OneToOne(targetEntity: Log::class)]
    private $log;

    public function __construct()
    {
        $this->sets = new ArrayCollection();
        $this->subjects = new ArrayCollection();
        $this->topics = new ArrayCollection();
        $this->resourceFiles = new ArrayCollection();
        $this->assignments = new ArrayCollection();
        $this->submissions = new ArrayCollection();
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
     * Deprecated - remove in Symfony 6.
     */
    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles[] = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles = [];
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
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

    public function addSet(Set $set): self
    {
        if (!$this->sets->contains($set)) {
            $this->sets[] = $set;
        }

        return $this;
    }

    public function removeSet(Set $set): self
    {
        if ($this->sets->contains($set)) {
            $this->sets->removeElement($set);
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
        if (!$this->sets->contains($$topic)) {
            $this->sets[] = $$topic;
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
     * @return Collection|Submission[]
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
        if ($this->submissions->contains($assigment)) {
            $this->submissions->removeElement($assigment);
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
}
