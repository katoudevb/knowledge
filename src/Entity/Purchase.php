<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a purchase made by a user.
 *
 * A purchase can be associated with either a course or a lesson.
 * It records the amount paid and tracks creation and update information.
 *
 * @property int|null $id Unique identifier of the purchase.
 * @property User|null $user The user who made the purchase.
 * @property Course|null $course The course purchased (if applicable).
 * @property Lesson|null $lesson The lesson purchased (if applicable).
 * @property float|null $amount Amount paid for the purchase.
 * @property \DateTimeImmutable|null $createdAt Timestamp when the purchase was created.
 * @property \DateTimeImmutable|null $updatedAt Timestamp when the purchase was last updated.
 * @property User|null $createdBy User who created the purchase record.
 * @property User|null $updatedBy User who last updated the purchase record.
 */
#[ORM\Entity(repositoryClass: PurchaseRepository::class)]
class Purchase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'purchases')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'purchases')]
    private ?Course $course = null;

    #[ORM\ManyToOne(inversedBy: 'purchases')]
    private ?Lesson $lesson = null;

    #[ORM\Column]
    private ?float $amount = null;

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
    public function getCourse(): ?Course { return $this->course; }
    public function setCourse(?Course $course): static { $this->course = $course; return $this; }
    public function getLesson(): ?Lesson { return $this->lesson; }
    public function setLesson(?Lesson $lesson): static { $this->lesson = $lesson; return $this; }
    public function getAmount(): ?float { return $this->amount; }
    public function setAmount(float $amount): static { $this->amount = $amount; return $this; }

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

    // -----------------------------
    // Specific Methods
    // -----------------------------
    /**
     * Performs a simulated purchase for sandbox/testing.
     */
    public function sandboxPurchase(User $user, $courseOrLesson, ?float $amount = null): void
    {
        $this->user = $user;

        if ($courseOrLesson instanceof Course) {
            $this->course = $courseOrLesson;
            $this->lesson = null;
            $this->amount = $amount ?? $courseOrLesson->getPrice();
        } elseif ($courseOrLesson instanceof Lesson) {
            $this->lesson = $courseOrLesson;
            $this->course = null;
            $this->amount = $amount ?? $courseOrLesson->getPrice();
        } else {
            throw new \InvalidArgumentException('Item must be Course or Lesson.');
        }

        $this->createdAt = new \DateTimeImmutable();
    }
}
