<?php
namespace App\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;


final class RulesAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('description', TextType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
        ->add('description');

    ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
        ->add('tournament', null, [
            'associated_property' => 'name'
        ])
        ->addIdentifier('description')
        ->addIdentifier('created_at');
    ;
    }
}