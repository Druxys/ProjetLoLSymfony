<?php
/**
 * Created by PhpStorm.
 * User: camille
 * Date: 30/10/2020
 * Time: 10:47
 */

namespace App\Form;


use App\Entity\Report;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateReportFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('summoner_lol', EntityType::class,[
            'class' => User::class,
            'choice_label' => "reports"
            ])
            ->add('reason', TextType::class);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null
        ]);
    }
}