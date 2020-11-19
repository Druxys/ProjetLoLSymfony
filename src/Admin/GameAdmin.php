<?php
namespace App\Admin;

use App\Entity\Tournament;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
final class GameAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('team_1', NumberType::class)
        ->add('team_2', NumberType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
        ->add('team_1');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
        ->add('tournament', null, [], EntityType::class, [
            'class' => Tournament::class,
            'choice_label' => 'name',
        ])
        ->add('team_1', ModelType::class, [
            'class' => Team::class,
            'property' => 'name',
        ])
        ->add('team_2', ModelType::class, [
            'class' => Team::class,
            'property' => 'name',
        ])
        ->add('created_at');
        
    }
}