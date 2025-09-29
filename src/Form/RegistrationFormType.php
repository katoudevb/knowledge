<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * Registration form type for new users.
 * 
 * Handles email, password, and terms acceptance.
 */
class RegistrationFormType extends AbstractType
{
    /**
     * Builds the registration form.
     *
     * Adds fields for email, plain password, and terms agreement.
     *
     * @param FormBuilderInterface $builder The form builder instance
     * @param array<string, mixed> $options Options passed during form creation
     * 
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email') // Input field for user's email

            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false, // Not mapped to the User entity
                'constraints' => [
                    new IsTrue(message: 'Vous devez accepter les conditions.'), // Validation: must be checked
                ],
            ])

            ->add('plainPassword', PasswordType::class, [
            'mapped' => false,
            'attr' => ['autocomplete' => 'new-password'],
            'constraints' => [
                new NotBlank(message: 'Veuillez entrer un mot de passe'),
                new Length(
                    min: 8,
                    minMessage: 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                    max: 4096
                ),
                new Regex(
                    pattern: '/[A-Z]/',
                    message: 'Le mot de passe doit contenir au moins une majuscule'
                ),
                new Regex(
                    pattern: '/[a-z]/',
                    message: 'Le mot de passe doit contenir au moins une minuscule'
                ),
                new Regex(
                    pattern: '/\d/',
                    message: 'Le mot de passe doit contenir au moins un chiffre'
                ),
                new Regex(
                    pattern: '/[\W]/',
                    message: 'Le mot de passe doit contenir au moins un caractère spécial'
                ),
            ],
        ]);
    }

    /**
     * Configures the options for this form type.
     *
     * Sets the data class associated with this form to User and enables CSRF protection.
     *
     * @param OptionsResolver $resolver The options resolver instance
     * 
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class, // Link form to the User entity
            'csrf_protection' => true,   // CSRF protection enabled
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'registration_form',
        ]);
    }
}
