<?php

namespace App\Form\Back;

use App\Entity\Course;
use App\Entity\Lesson;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type for managing Lesson entities in the backend.
 * Includes fields for title, price, content, video URL, and associated course.
 */
class LessonType extends AbstractType
{
    /**
     * Build the form for the Lesson entity.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array $options Additional options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de la leçon', // Lesson title
            ])
            ->add('price', TextType::class, [
                'label' => 'Prix', // Price
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Description / Contenu', // Lesson content / description
                'required' => false,
                'attr' => [
                    'rows' => 10, // more space for editing
                    'placeholder' => '<h3>Description :</h3><p>Texte de la leçon ici...</p>'
                ],
                'empty_data' => '<h3>Description :</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>'
            ])
            ->add('videoUrl', UrlType::class, [
                'label' => 'URL de la vidéo', // Video URL
                'required' => false,
                'attr' => ['placeholder' => 'https://...'],
            ])
            ->add('course', EntityType::class, [
                'class' => Course::class,
                'choice_label' => 'id',
                'label' => 'Cours associé', // Associated course
            ]);
    }

    /**
     * Configure default options for this form.
     *
     * @param OptionsResolver $resolver The options resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lesson::class,
        ]);
    }
}
