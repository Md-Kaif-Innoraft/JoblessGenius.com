<?php

namespace App\Form;

use App\Entity\Profile;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('name')
      ->add('college_name')
      ->add('email')
      ->add('degree')
      ->add('branch')
      ->add('graduation_cgpa')
      ->add('school_name_12')
      ->add('per_12')
      ->add('school_name_10')
      ->add('per_10')
      ->add('resume')
      ->add('user_id', EntityType::class, [
        'class' => User::class,
        'choice_label' => 'id',
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Profile::class,
    ]);
  }
}
