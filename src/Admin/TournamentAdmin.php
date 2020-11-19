<?php
namespace App\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class TournamentAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
        ->add('name', TextType::class)
        ->add('start_tournament', DateType::class)
        ->add('end_tournament', DateType::class)
        ->add('numbers_participants', NumberType::class)
        ->add('type_tournament', TextType::class)
        ->add('group_stage', ChoiceType::class, [
            'choices' => [
                'active' => true,
                'inactive' => false
            ]
        ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
        ->add('name')
        ->add('start_tournament')
        ->add('end_tournament')
        ->add('numbers_participants')
        ->add('type_tournament')
        ->add('group_stage');

    ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
        ->addIdentifier('name')
        ->addIdentifier('start_tournament')
        ->addIdentifier('end_tournament')
        ->addIdentifier('numbers_participants')
        ->addIdentifier('type_tournament')
        ->addIdentifier('group_stage');
    ;
    }
}