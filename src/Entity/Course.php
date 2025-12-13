<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a course offered on the platform.
 *
 * A course belongs to a theme and may contain multiple lessons.
 * It can also be associated with purchases made by users and certifications earned.
 *
 * @property int|null $id Unique identifier of the course.
 * @property string|null $title The title of the course.
 * @property float|null $price The price of the course.
 * @property string|null $description The course description.
 * @property Theme|null $theme The theme under which this course falls.
 * @property Collection<int, Lesson> $lessons Collection of lessons included in this course.
 * @property Collection<int, Purchase> $purchases Collection of purchases associated with this course.
 * @property Collection<int, Certification> $certifications Collection of certifications linked to this course.
 * @property \DateTimeImmutable|null $createdAt Timestamp when the course was created.
 * @property \DateTimeImmutable|null $updatedAt Timestamp when the course was last updated.
 * @property User|null $createdBy User who created this course.
 * @property User|null $updatedBy User who last updated this course.
 */
#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'courses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Theme $theme = null;

    #[ORM\OneToMany(targetEntity: Lesson::class, mappedBy: 'course')]
    private Collection $lessons;

    #[ORM\OneToMany(targetEntity: Purchase::class, mappedBy: 'course')]
    private Collection $purchases;

    #[ORM\OneToMany(targetEntity: Certification::class, mappedBy: 'course')]
    private Collection $certifications;

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
        $this->lessons = new ArrayCollection();
        $this->purchases = new ArrayCollection();
        $this->certifications = new ArrayCollection();
    }

    // -----------------------------
    // Getters & Setters
    // -----------------------------
    public function getId(): ?int { return $this->id; }
    public function getTitle(): ?string { return $this->title; }
    public function setTitle(string $title): static { $this->title = $title; return $this; }
    public function getPrice(): ?float { return $this->price; }
    public function setPrice(float $price): static { $this->price = $price; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): static { $this->description = $description; return $this; }
    public function getTheme(): ?Theme { return $this->theme; }
    public function setTheme(?Theme $theme): static { $this->theme = $theme; return $this; }
    public function getLessons(): Collection { return $this->lessons; }
    public function addLesson(Lesson $lesson): static
    {
        if (!$this->lessons->contains($lesson)) {
            $this->lessons->add($lesson);
            $lesson->setCourse($this);
        }
        return $this;
    }
    public function removeLesson(Lesson $lesson): static
    {
        if ($this->lessons->removeElement($lesson)) {
            if ($lesson->getCourse() === $this) $lesson->setCourse(null);
        }
        return $this;
    }
    public function getPurchases(): Collection { return $this->purchases; }
    public function addPurchase(Purchase $purchase): static
    {
        if (!$this->purchases->contains($purchase)) {
            $this->purchases->add($purchase);
            $purchase->setCourse($this);
        }
        return $this;
    }
    public function removePurchase(Purchase $purchase): static
    {
        if ($this->purchases->removeElement($purchase)) {
            if ($purchase->getCourse() === $this) $purchase->setCourse(null);
        }
        return $this;
    }
    public function getCertifications(): Collection { return $this->certifications; }
    public function addCertification(Certification $certification): static
    {
        if (!$this->certifications->contains($certification)) {
            $this->certifications->add($certification);
            $certification->setCourse($this);
        }
        return $this;
    }
    public function removeCertification(Certification $certification): static
    {
        if ($this->certifications->removeElement($certification)) {
            if ($certification->getCourse() === $this) $certification->setCourse(null);
        }
        return $this;
    }

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
