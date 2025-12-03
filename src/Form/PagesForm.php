<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Entity\Pages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PagesForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('lien', TextType::class, [
                'required' => false,
                'empty_data' => '',
            ])
            ->add('rang')
            ->add('emplacement', ChoiceType::class, [
                'choices' => [
                    'Ma prise en main' => 'Ma prise en main',
                    'Tools' => 'Tools',
                    'Le guide du banquier' => 'Le guide du banquier'
                ],
                'label' => false,
            ])
            ->add('contenu', TextareaType::class, [
                'required' => false,
                'attr' => ['hidden' => 'hidden'], // champ caché (sera alimenté par Quill)
            ])
            ->add('style', TextareaType::class, [
                'required' => false,
                'attr' => ['hidden' => 'hidden'], // champ caché (sera alimenté par Quill)
            ])
            ->add('script', TextareaType::class, [
                'required' => false,
                'attr' => ['hidden' => 'hidden'], // champ caché (sera alimenté par Quill)
            ])
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'En ligne' => true,
                    'Hors ligne' => false
                ],
                'label' => false,
            ])
            ->add('cover', FileType::class, [
                'label' => false,
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier image valide (JPEG, PNG)',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pages::class,
        ]);
    }
}
