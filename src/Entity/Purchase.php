<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use Doctrine\ORM\Mapping as ORM;

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
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?float $amount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): static
    {
        $this->course = $course;
        return $this;
    }

    public function getLesson(): ?Lesson
    {
        return $this->lesson;
    }

    public function setLesson(?Lesson $lesson): static
    {
        $this->lesson = $lesson;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    // -----------------------------
    // AJOUT : méthode sandboxPurchase
    // -----------------------------
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
    
    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }
}
