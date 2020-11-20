<?php
namespace App\Admin;

use App\Entity\Team;
use App\Entity\User;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
final class UsersTeamAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper ->add('user', ModelType::class, [
            'class' => User::class,
            'property' => 'summoner_lol',
        ])
        ->add('team', ModelType::class, [
            'class' => Team::class,
            'property' => 'name',
        ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
        ->add('user');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
        ->add('user', null, [], EntityType::class, [
            'class' => User::class,
            'choice_label' => 'summoner_lol',
        ])
        ->add('team', null, [], EntityType::class, [
            'class' => Team::class,
            'choice_label' => 'name',
        ]);
    }
}