<?php
namespace App\Admin;

use App\Entity\Tournament;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class RulesAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('description', TextType::class)
        ->add('updated_at', DateType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
        ->add('description');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
        ->add('tournament', null, [], EntityType::class, [
            'class' => Tournament::class,
            'choice_label' => 'name',
        ])
        ->addIdentifier('description')
        ->add('created_at')
        ->add('updated_at');
    }
}