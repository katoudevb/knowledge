<?php

namespace App\Service;

use App\Entity\Lesson;
use Doctrine\ORM\EntityManagerInterface;

class LessonsService
{
    public function __construct(private EntityManagerInterface $em) {}

    /**
     * Get all lessons.
     *
     * @return Lesson[]
     */
    public function getAllLessons(): array
    {
        return $this->em->getRepository(Lesson::class)->findAll();
    }

    /**
     * Save a lesson (create or update).
     */
    public function saveLesson(Lesson $lesson): void
    {
        $this->em->persist($lesson);
        $this->em->flush();
    }

    /**
     * Delete a lesson.
     */
    public function deleteLesson(Lesson $lesson): void
    {
        $this->em->remove($lesson);
        $this->em->flush();
    }
}
