<?php

namespace App\Entity;

use App\Repository\ThemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a theme grouping multiple courses.
 *
 * Examples: "Music", "Computer Science", etc.
 *
 * @property int|null $id
 * @property string|null $name
 * @property Collection<int, Course> $courses
 * @property \DateTimeImmutable|null $createdAt
 * @property \DateTimeImmutable|null $updatedAt
 * @property User|null $createdBy
 * @property User|null $updatedBy
 */
#[ORM\Entity(repositoryClass: ThemeRepository::class)]
class Theme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: Course::class, mappedBy: 'theme')]
    private Collection $courses;

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
    // Runtime property for accessible courses
    // -----------------------------
    private array $accessibleCourses = [];

    public function __construct()
    {
        $this->courses = new ArrayCollection();
    }

    // -----------------------------
    // Getters & Setters
    // -----------------------------
    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }
    public function getCourses(): Collection { return $this->courses; }
    public function addCourse(Course $course): static
    {
        if (!$this->courses->contains($course)) {
            $this->courses->add($course);
            $course->setTheme($this);
        }
        return $this;
    }
    public function removeCourse(Course $course): static
    {
        if ($this->courses->removeElement($course)) {
            if ($course->getTheme() === $this) {
                $course->setTheme(null);
            }
        }
        return $this;
    }

    // -----------------------------
    // Accessible courses (runtime only)
    // -----------------------------
    public function getAccessibleCourses(): array
    {
        return $this->accessibleCourses;
    }

    public function setAccessibleCourses(array $courses): static
    {
        $this->accessibleCourses = $courses;
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
