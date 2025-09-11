<?php

namespace App\Form\Back;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type for creating or editing a User entity.
 * 
 * This form is used in the back-office to manage users.
 */
class UserType extends AbstractType
{
    /**
     * Builds the user form.
     *
     * Adds fields for email, password, and verification status.
     * Note: roles field is omitted to avoid "Array to string conversion" errors.
     *
     * @param FormBuilderInterface $builder The form builder instance
     * @param array<string, mixed> $options Options passed during form creation
     * 
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')        // Input field for the user's email
            // ->add('roles')     // Omitted to prevent "Array to string conversion" errors
            ->add('password')     // Input field for the user's password
            ->add('isVerified');  // Checkbox or boolean field for email verification status
    }

    /**
     * Configures the options for this form type.
     *
     * Sets the data class associated with this form to User.
     *
     * @param OptionsResolver $resolver The options resolver instance
     * 
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class, // Link form to the User entity
        ]);
    }
}
