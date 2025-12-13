<?php

namespace App\Entity;

use App\Repository\LessonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a lesson within a course.
 *
 * A lesson belongs to a specific course and can be linked to multiple purchases
 * and multiple user entries (UserLesson) to track lesson completion.
 *
 * @property int|null $id Unique identifier of the lesson.
 * @property string|null $title Title of the lesson.
 * @property float|null $price Price of the lesson.
 * @property string|null $content Textual content of the lesson.
 * @property Course|null $course The course to which this lesson belongs.
 * @property Theme|null $theme The theme of the lesson (inherited from the course).
 * @property Collection<int, Purchase> $purchases Purchases associated with this lesson.
 * @property Collection<int, UserLesson> $userLessons Tracks user progress for this lesson.
 * @property string|null $videoUrl URL of the lesson video, if available.
 * @property \DateTimeImmutable|null $createdAt Timestamp when the lesson was created.
 * @property \DateTimeImmutable|null $updatedAt Timestamp when the lesson was last updated.
 * @property User|null $createdBy User who created the lesson.
 * @property User|null $updatedBy User who last updated the lesson.
 */
#[ORM\Entity(repositoryClass: LessonRepository::class)]
class Lesson
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'lessons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Course $course = null;

    // -----------------------------
    // New relation: Theme
    // -----------------------------
    #[ORM\ManyToOne(targetEntity: Theme::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Theme $theme = null;

    #[ORM\OneToMany(targetEntity: Purchase::class, mappedBy: 'lesson')]
    private Collection $purchases;

    #[ORM\OneToMany(targetEntity: UserLesson::class, mappedBy: 'lesson', orphanRemoval: true)]
    private Collection $userLessons;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $videoUrl = null;

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

    public function __construct()
    {
        $this->purchases = new ArrayCollection();
        $this->userLessons = new ArrayCollection();
    }

    // -----------------------------
    // Getters / Setters
    // -----------------------------
    public function getId(): ?int { return $this->id; }
    public function getTitle(): ?string { return $this->title; }
    public function setTitle(string $title): static { $this->title = $title; return $this; }
    public function getPrice(): ?float { return $this->price; }
    public function setPrice(float $price): static { $this->price = $price; return $this; }
    public function getContent(): ?string { return $this->content; }
    public function setContent(?string $content): static { $this->content = $content; return $this; }
    public function getCourse(): ?Course { return $this->course; }
    public function setCourse(?Course $course): static { $this->course = $course; return $this; }

    // -----------------------------
    // Theme getters / setters
    // -----------------------------
    public function getTheme(): ?Theme { return $this->theme; }
    public function setTheme(?Theme $theme): static { $this->theme = $theme; return $this; }

    public function getPurchases(): Collection { return $this->purchases; }
    public function addPurchase(Purchase $purchase): static
    {
        if (!$this->purchases->contains($purchase)) {
            $this->purchases->add($purchase);
            $purchase->setLesson($this);
        }
        return $this;
    }
    public function removePurchase(Purchase $purchase): static
    {
        if ($this->purchases->removeElement($purchase)) {
            if ($purchase->getLesson() === $this) $purchase->setLesson(null);
        }
        return $this;
    }
    public function getUserLessons(): Collection { return $this->userLessons; }
    public function addUserLesson(UserLesson $userLesson): static
    {
        if (!$this->userLessons->contains($userLesson)) {
            $this->userLessons->add($userLesson);
            $userLesson->setLesson($this);
        }
        return $this;
    }
    public function removeUserLesson(UserLesson $userLesson): static
    {
        if ($this->userLessons->removeElement($userLesson)) {
            if ($userLesson->getLesson() === $this) $userLesson->setLesson(null);
        }
        return $this;
    }
    public function getVideoUrl(): ?string { return $this->videoUrl; }
    public function setVideoUrl(?string $videoUrl): static { $this->videoUrl = $videoUrl; return $this; }

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
