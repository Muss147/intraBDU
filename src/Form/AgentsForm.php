<?php

namespace App\Form;

use App\Entity\Agents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgentsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenoms')
            ->add('civilite', ChoiceType::class, [
                'choices' => [
                    'Monsieur' => 'M.',
                    'Madame' => 'Mme.',
                    'Mademoiselle' => 'Mlle.',
                ],
                'placeholder' => 'Choisir une civilité',
                'required' => true,
                'label' => false,
            ])
            ->add('telephone')
            ->add('email', EmailType::class)
            ->add('fonction')
            ->add('anniversaire')
            ->add('photo', FileType::class, [
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
            'data_class' => Agents::class,
        ]);
    }
}
