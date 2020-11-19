<?php
namespace App\Admin;

use Sonata\Form\Type\CollectionType;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class UserAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
        ->add('email', TextType::class)
        ->add('password', PasswordType::class)
        ->add('summoner_lol', TextType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
        ->add('email')
        ->add('password')
        ->add('summoner_lol');

    ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
        ->addIdentifier('email')
        ->addIdentifier('summoner_lol')
        ->addIdentifier('roles')
        ->addIdentifier('created_at')
        ->addIdentifier('is_banned')
    ;
    }
}