<?php

namespace App\Form\Back;

use App\Entity\Theme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type for creating or editing a Theme entity.
 * 
 * This form is used in the back-office to manage themes.
 */
class ThemeType extends AbstractType
{
    /**
     * Builds the theme form.
     *
     * Adds a field for the theme name.
     *
     * @param FormBuilderInterface $builder The form builder instance
     * @param array<string, mixed> $options Options passed during form creation
     * 
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name'); // Input field for the theme name
    }

    /**
     * Configures the options for this form type.
     *
     * Sets the data class associated with this form to Theme.
     *
     * @param OptionsResolver $resolver The options resolver instance
     * 
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Theme::class, // Link form to the Theme entity
        ]);
    }
}
