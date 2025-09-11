<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Represents an application user.
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'An account with this email already exists')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = ['ROLE_CLIENT'];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private ?bool $isVerified = false;

    #[ORM\OneToMany(targetEntity: Purchase::class, mappedBy: 'user')]
    private Collection $achats;

    #[ORM\OneToMany(
        targetEntity: Certification::class,
        mappedBy: 'user',
        cascade: ['persist', 'remove'], // <-- ajout 'remove'
        orphanRemoval: true
    )]
    private Collection $certifications;

    #[ORM\OneToMany(targetEntity: UserLesson::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $leconsUtilisateur;

    #[ORM\Column(name: "created_at", nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(name: "updated_at", nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: self::class)]
    #[ORM\JoinColumn(name: "created_by", referencedColumnName: "id", nullable: true)]
    private ?self $createdBy = null;

    #[ORM\ManyToOne(targetEntity: self::class)]
    #[ORM\JoinColumn(name: "updated_by", referencedColumnName: "id", nullable: true)]
    private ?self $updatedBy = null;

    public function __construct()
    {
        $this->achats = new ArrayCollection();
        $this->certifications = new ArrayCollection();
        $this->leconsUtilisateur = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): static { $this->email = $email; return $this; }
    public function getUserIdentifier(): string { return (string) $this->email; }
    public function getRoles(): array { return array_unique(array_merge($this->roles, ['ROLE_CLIENT'])); }
    public function setRoles(array $roles): static { $this->roles = $roles; return $this; }
    public function getPassword(): ?string { return $this->password; }
    public function setPassword(string $password): static { $this->password = $password; return $this; }
    #[\Deprecated] public function eraseCredentials(): void {}
    public function isVerified(): ?bool { return $this->isVerified; }
    public function setIsVerified(bool $isVerified): static { $this->isVerified = $isVerified; return $this; }

    // Purchases
    public function getAchats(): Collection { return $this->achats; }
    public function addAchat(Purchase $achat): static
    {
        if (!$this->achats->contains($achat)) {
            $this->achats->add($achat);
            $achat->setUser($this);
        }
        return $this;
    }
    public function removeAchat(Purchase $achat): static
    {
        if ($this->achats->removeElement($achat) && $achat->getUser() === $this) {
            $achat->setUser(null);
        }
        return $this;
    }

    // Certifications
    public function getCertifications(): Collection { return $this->certifications; }
    public function addCertification(Certification $certification): static
    {
        if (!$this->certifications->contains($certification)) {
            $this->certifications->add($certification);
            $certification->setUser($this);
        }
        return $this;
    }
    public function removeCertification(Certification $certification): static
    {
        if ($this->certifications->removeElement($certification) && $certification->getUser() === $this) {
            $certification->setUser(null);
        }
        return $this;
    }

    public function getFrontCertifications(): Collection
    {
        return $this->getCertifications();
    }

    // User Lessons
    public function getLeconsUtilisateur(): Collection { return $this->leconsUtilisateur; }
    public function addLeconUtilisateur(UserLesson $lecon): static
    {
        if (!$this->leconsUtilisateur->contains($lecon)) {
            $this->leconsUtilisateur->add($lecon);
            $lecon->setUser($this);
        }
        return $this;
    }
    public function removeLeconUtilisateur(UserLesson $lecon): static
    {
        if ($this->leconsUtilisateur->removeElement($lecon) && $lecon->getUser() === $this) {
            $lecon->setUser(null);
        }
        return $this;
    }

    // Front-end helpers
    public function hasValidatedLesson(Lesson $lecon): bool
    {
        foreach ($this->leconsUtilisateur as $userLesson) {
            if ($userLesson->getLesson() === $lecon && $userLesson->isValidated()) {
                return true;
            }
        }
        return false;
    }

    public function addCertificationFromCourse(Course $cours): static
    {
        foreach ($this->certifications as $cert) {
            if ($cert->getCourse() === $cours) return $this;
        }

        $certification = new Certification();
        $certification->setUser($this)
                      ->setCourse($cours)
                      ->setObtainedAt(new \DateTimeImmutable());

        $this->certifications->add($certification);
        return $this;
    }

    // Audit Getters / Setters
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): static { $this->createdAt = $createdAt; return $this; }
    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }
    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static { $this->updatedAt = $updatedAt; return $this; }
    public function getCreatedBy(): ?self { return $this->createdBy; }
    public function setCreatedBy(?self $createdBy): static { $this->createdBy = $createdBy; return $this; }
    public function getUpdatedBy(): ?self { return $this->updatedBy; }
    public function setUpdatedBy(?self $updatedBy): static { $this->updatedBy = $updatedBy; return $this; }
}
