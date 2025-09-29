<?php

namespace App\Service;

use App\Entity\Theme;
use App\Entity\Course;
use Doctrine\ORM\EntityManagerInterface;

class ThemeService
{
    public function __construct(private EntityManagerInterface $em) {}

    /**
     * Get all themes.
     *
     * @return Theme[]
     */
    public function getAllThemes(): array
    {
        return $this->em->getRepository(Theme::class)->findAll();
    }

    /**
     * Get a theme with its courses by ID.
     */
    public function getThemeWithCourses(int $themeId): ?Theme
    {
        return $this->em->getRepository(Theme::class)->find($themeId);
    }

    /**
     * Add a course to a theme.
     */
    public function addCourseToTheme(Theme $theme, Course $course): void
    {
        $theme->addCourse($course); // Assurez-vous que l'entité Theme a bien addCourse()
        $this->em->persist($theme);
        $this->em->flush();
    }

    /**
     * Save a theme (create or update).
     */
    public function saveTheme(Theme $theme): void
    {
        $this->em->persist($theme);
        $this->em->flush();
    }

    /**
     * Delete a theme.
     */
    public function deleteTheme(Theme $theme): void
    {
        $this->em->remove($theme);
        $this->em->flush();
    }
}
