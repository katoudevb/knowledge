<?php

namespace App\Form\Back;

use App\Entity\Course;
use App\Entity\Lesson;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type for creating or editing a Lesson entity.
 * 
 * This form is used in the back-office to manage lessons.
 */
class LessonType extends AbstractType
{
    /**
     * Builds the lesson form.
     *
     * Adds fields for title, price, content, and associated course.
     *
     * @param FormBuilderInterface $builder The form builder instance
     * @param array<string, mixed> $options Options passed during form creation
     * 
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title') // Input field for the lesson title
            ->add('price') // Input field for the lesson price
            ->add('content') // Input field for the textual content of the lesson
            ->add('course', EntityType::class, [ // Dropdown to select the associated course
                'class' => Course::class,
                'choice_label' => 'id', // Display the course ID as the label
            ]);
    }

    /**
     * Configures the options for this form type.
     *
     * Sets the data class associated with this form to Lesson.
     *
     * @param OptionsResolver $resolver The options resolver instance
     * 
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lesson::class, // Link form to the Lesson entity
        ]);
    }
}
