<?php

namespace App\Entity;

use App\Repository\UserLessonRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Pivot entity representing the relationship between a user and a lesson.
 *
 * Tracks whether a user has validated a lesson and the date of validation.
 *
 * @property int|null $id Unique identifier of the UserLesson.
 * @property User|null $user Associated user.
 * @property Lesson|null $lesson Associated lesson.
 * @property bool|null $validated Indicates if the lesson has been validated.
 * @property \DateTimeImmutable|null $validatedAt Date when the lesson was validated.
 * @property \DateTimeImmutable|null $createdAt Creation timestamp.
 * @property \DateTimeImmutable|null $updatedAt Last update timestamp.
 * @property User|null $createdBy User who created this entry.
 * @property User|null $updatedBy User who last updated this entry.
 */
#[ORM\Entity(repositoryClass: UserLessonRepository::class)]
class UserLesson
{
    /** @var int|null Primary key of the UserLesson */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /** @var User|null Associated user */
    #[ORM\ManyToOne(inversedBy: 'userLessons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /** @var Lesson|null Associated lesson */
    #[ORM\ManyToOne(inversedBy: 'userLessons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Lesson $lesson = null;

    /** @var bool|null Whether the lesson has been validated */
    #[ORM\Column]
    private ?bool $validated = false;

    /** @var \DateTimeImmutable|null Date of validation */
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $validatedAt = null;

    // -----------------------------
    // Audit fields
    // -----------------------------
    #[ORM\Column(name: "created_at", nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(name: "updated_at", nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "created_by", referencedColumnName: "id", nullable: true)]
    private ?User $createdBy = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "updated_by", referencedColumnName: "id", nullable: true)]
    private ?User $updatedBy = null;

    // -----------------------------
    // Getters / Setters
    // -----------------------------
    public function getId(): ?int { return $this->id; }
    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): static { $this->user = $user; return $this; }
    public function getLesson(): ?Lesson { return $this->lesson; }
    public function setLesson(?Lesson $lesson): static { $this->lesson = $lesson; return $this; }
    public function isValidated(): ?bool { return $this->validated; }
    public function setValidated(bool $validated): static { $this->validated = $validated; return $this; }
    public function getValidatedAt(): ?\DateTimeImmutable { return $this->validatedAt; }
    public function setValidatedAt(?\DateTimeImmutable $validatedAt): static { $this->validatedAt = $validatedAt; return $this; }

    // -----------------------------
    // Audit Getters / Setters
    // -----------------------------
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): static { $this->createdAt = $createdAt; return $this; }
    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }
    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static { $this->updatedAt = $updatedAt; return $this; }
    public function getCreatedBy(): ?User { return $this->createdBy; }
    public function setCreatedBy(?User $createdBy): static { $this->createdBy = $createdBy; return $this; }
    public function getUpdatedBy(): ?User { return $this->updatedBy; }
    public function setUpdatedBy(?User $updatedBy): static { $this->updatedBy = $updatedBy; return $this; }
}
