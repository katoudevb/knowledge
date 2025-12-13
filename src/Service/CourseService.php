<?php

namespace App\Service;

use App\Entity\Course;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Service handling business logic related to Course entity.
 *
 * Centralizes all CRUD operations and can be reused in controllers.
 */
class CourseService
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    /**
     * Returns all courses from the database.
     *
     * @return Course[]
     */
    public function getAllCourses(): array
    {
        return $this->em->getRepository(Course::class)->findAll();
    }

    /**
     * Persists a new course to the database.
     *
     * @param Course $course
     */
    public function createCourse(Course $course): void
    {
        $this->em->persist($course);
        $this->em->flush();
    }

    /**
     * Updates an existing course.
     *
     * @param Course $course
     */
    public function updateCourse(Course $course): void
    {
        $this->em->flush();
    }

    /**
     * Deletes a course from the database.
     *
     * @param Course $course
     */
    public function deleteCourse(Course $course): void
    {
        $this->em->remove($course);
        $this->em->flush();
    }
}
