<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="person")
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class Person implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $disabled;

    /**
     * @ORM\ManyToMany(targetEntity="Set", inversedBy="people", cascade={"persist"})
     * @ORM\JoinTable(name="people_sets")
     * @var Collection
     */
    private $sets;

    /**
     * @ORM\OneToMany(targetEntity="ResourceFile", mappedBy="owner")
     */
    private $resourceFiles;

    /**
     * @ORM\OneToMany(targetEntity="Submission", mappedBy="owner")
     */
    private $submissions;

    public function __construct()
    {
        $this->sets = new ArrayCollection();
        $this->resourceFiles = new ArrayCollection();
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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = "ROLE_USER";

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
            $file->setOwner($this);
        }

        return $this;
    }

    public function removeResourceFile(ResourceFile $file): self
    {
        if ($this->resourceFiles->contains($file)) {
            $this->resourceFiles->removeElement($file);
            // set the owning side to null (unless already changed)
            if ($file->getOwner() === $this) {
                $file->setOwner(null);
            }
        }

        return $this;
    }

    public function getSubmission(): ?Submission
    {
        return $this->submission;
    }

    public function setSubmission(Submission $submission): self
    {
        $this->submission = $submission;

        // set the owning side of the relation if necessary
        if ($submission->getOwner() !== $this) {
            $submission->setOwner($this);
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
            $submission->setOwner($this);
        }

        return $this;
    }

    public function removeSubmission(Submission $submission): self
    {
        if ($this->submissions->contains($submission)) {
            $this->submissions->removeElement($submission);
            // set the owning side to null (unless already changed)
            if ($submission->getOwner() === $this) {
                $submission->setOwner(null);
            }
        }

        return $this;
    }
}
