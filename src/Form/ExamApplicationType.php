<?php

namespace App\Form;

use App\Entity\Exam;
use App\Entity\User;
use App\Entity\ExamApplication;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ExamApplicationType extends AbstractType
{

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
    ->add('applied_at', DateTimeType::class, [
      // Render the datetime field as a single text input
      'widget' => 'single_text',
      // Optional label for the field
      'label' => 'Applied At',
      // Set the default value to the current datetime
      'data' => new \DateTime(),
    ])
      ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => ExamApplication::class,
    ]);
  }
}
