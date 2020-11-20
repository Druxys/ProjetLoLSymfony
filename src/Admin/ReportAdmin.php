<?php
namespace App\Admin;

use App\Entity\User;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

final class ReportAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper ->add('user', ModelType::class, [
            'class' => User::class,
            'property' => 'summoner_lol',
        ])
            ->add('reason', TextType::class)
            ->add('IdUserReported', NumberType::class);


    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
        ->add('reason');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
        ->add('user', null, [], EntityType::class, [
            'class' => User::class,
            'choice_label' => 'summoner_lol',
        ])
        ->addIdentifier('reason')
        ->add('IdUserReported')
        ->addIdentifier('created_at');
    }
}