<?php
/**
 * Created by PhpStorm.
 * User: camille
 * Date: 05/11/2020
 * Time: 11:32
 */

namespace App\Form;


use App\Entity\User;
use App\Entity\UsersTeams;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SendInvitationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('summoner_lol', TextType::class);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }

}
