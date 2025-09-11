<?php

namespace App\Form\Back;

use App\Entity\Course;
use App\Entity\Theme;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type for creating or editing a Course entity.
 * 
 * This form is used in the back-office to manage course records.
 */
class CourseType extends AbstractType
{
    /**
     * Builds the course form.
     *
     * Adds fields for title, price, and associated theme.
     *
     * @param FormBuilderInterface $builder The form builder instance
     * @param array<string, mixed> $options Options passed during form creation
     * 
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title') // Input field for the course title
            ->add('price') // Input field for the course price
            ->add('theme', EntityType::class, [ // Dropdown to select the related theme
                'class' => Theme::class,
                'choice_label' => 'id', // Display the theme ID as the label
            ]);
    }

    /**
     * Configures the options for this form type.
     *
     * Sets the data class associated with this form to Course.
     *
     * @param OptionsResolver $resolver The options resolver instance
     * 
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Course::class, // Link form to the Course entity
        ]);
    }
}
