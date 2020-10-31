<?php

namespace App\Form;

use App\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UpdateGameFormType extends AbstractType {


public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder->add('team_1', IntegerType::class)
        ->add('team_2', IntegerType::class);
}


public function configureOptions(OptionsResolver $resolver)
{
    $resolver->setDefaults([
        'data_class' => Game::class
    ]);
}

}