<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

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

    // ---- Purchases (renamed) ----
    #[ORM\OneToMany(targetEntity: Purchase::class, mappedBy: 'user')]
    private Collection $purchases;

    // ---- Certifications ----
    #[ORM\OneToMany(targetEntity: Certification::class, mappedBy: 'user', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $certifications;

    // ---- User Lessons (renamed) ----
    #[ORM\OneToMany(targetEntity: UserLesson::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $userLessons;

    // Purchased Courses (unchanged)
    #[ORM\ManyToMany(targetEntity: Course::class)]
    #[ORM\JoinTable(name: "user_courses")]
    private Collection $purchasedCourses;

    // Audit fields
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
        $this->purchases = new ArrayCollection();
        $this->certifications = new ArrayCollection();
        $this->userLessons = new ArrayCollection();
        $this->purchasedCourses = new ArrayCollection();
    }

    // BASIC FIELDS
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

    // ---- PURCHASES ----
    public function getPurchases(): Collection { return $this->purchases; }

    public function addPurchase(Purchase $purchase): static
    {
        if (!$this->purchases->contains($purchase)) {
            $this->purchases->add($purchase);
            $purchase->setUser($this);
        }
        return $this;
    }

    public function removePurchase(Purchase $purchase): static
    {
        if ($this->purchases->removeElement($purchase) && $purchase->getUser() === $this) {
            $purchase->setUser(null);
        }
        return $this;
    }

    // ---- CERTIFICATIONS ----
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

    // ---- USER LESSONS ----
    public function getUserLessons(): Collection { return $this->userLessons; }

    public function addUserLesson(UserLesson $lesson): static
    {
        if (!$this->userLessons->contains($lesson)) {
            $this->userLessons->add($lesson);
            $lesson->setUser($this);
        }
        return $this;
    }

    public function removeUserLesson(UserLesson $lesson): static
    {
        if ($this->userLessons->removeElement($lesson) && $lesson->getUser() === $this) {
            $lesson->setUser(null);
        }
        return $this;
    }

    // ---- PURCHASED COURSES ----
    public function getPurchasedCourses(): Collection { return $this->purchasedCourses; }

    public function addPurchasedCourse(Course $course): self
    {
        if (!$this->purchasedCourses->contains($course)) {
            $this->purchasedCourses->add($course);
        }
        return $this;
    }

    public function removePurchasedCourse(Course $course): self
    {
        $this->purchasedCourses->removeElement($course);
        return $this;
    }

    // ---- HELPERS ----
    public function hasValidatedLesson(Lesson $lesson): bool
    {
        foreach ($this->userLessons as $userLesson) {
            if ($userLesson->getLesson() === $lesson && $userLesson->isValidated()) {
                return true;
            }
        }
        return false;
    }

    public function addCertificationFromCourse(Course $course): static
    {
        foreach ($this->certifications as $cert) {
            if ($cert->getCourse() === $course) return $this;
        }

        $certification = new Certification();
        $certification->setUser($this)
                      ->setCourse($course)
                      ->setObtainedAt(new \DateTimeImmutable());

        $this->certifications->add($certification);
        return $this;
    }

    // ---- AUDIT ----
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): static { $this->createdAt = $createdAt; return $this; }
    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }
    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static { $this->updatedAt = $updatedAt; return $this; }
    public function getCreatedBy(): ?self { return $this->createdBy; }
    public function setCreatedBy(?self $createdBy): static { $this->createdBy = $createdBy; return $this; }
    public function getUpdatedBy(): ?self { return $this->updatedBy; }
    public function setUpdatedBy(?self $updatedBy): static { $this->updatedBy = $updatedBy; return $this; }
}
