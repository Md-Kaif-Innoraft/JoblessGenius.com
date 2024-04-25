<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Profile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Image;

class ProfileType extends AbstractType
{

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('name', null, [
        'label' => 'Name:',
        'attr' => [
        'class' => 'form-control'
          ]
        ])
      ->add('college_name', null, [
        'label' => 'College Name:',
        'attr' => [
        'class' => 'form-control'
          ]
        ])
      ->add('email', null, [
        'label' => 'Email:',
        'attr' => [
        'class' => 'form-control'
          ]
        ])
      ->add('degree', null, [
        'label' => 'Degree:',
        'attr' => [
        'class' => 'form-control'
          ]
        ])
      ->add('branch', null, [
        'label' => 'Branch:',
        'attr' => [
        'class' => 'form-control'
          ]
        ])
      ->add('graduation_cgpa', null, [
        'label' => 'Graduation CGPA:',
        'attr' => [
        'class' => 'form-control'
          ]
        ])
      ->add('school_name_12', null, [
        'label' => 'School Name Class 12:',
        'attr' => [
        'class' => 'form-control'
          ]
        ])
      ->add('per_12', null, [
        'label' => 'Class 12 Percentage:',
        'attr' => [
        'class' => 'form-control'
          ]
        ])
      ->add('school_name_10', null, [
        'label' => 'School Name Class 10:',
        'attr' => [
        'class' => 'form-control'
          ]
        ])
      ->add('per_10', null, [
        'label' => 'Class 10 Percentage:',
        'attr' => [
        'class' => 'form-control'
          ]
        ])
      ->add('resume', null, [
        'label' => 'Resume Link:',
        'attr' => [
        'class' => 'form-control'
          ]
        ])
      ->add('user_id', EntityType::class, [
        'class' => User::class,
        'choice_label' => 'id',
      ])
      ->add('Image', FileType::class, [
                'label' => 'Upload File',
                'required' => true,
                'constraints' => [
                  new Image(['maxSize' => '10000K'])
                ],
                'attr' => [
                    'class' => 'form-control-file'
                ]
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Profile::class,
    ]);
  }
}
