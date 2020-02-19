<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Advertisement;

final class AdvertisementAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', TextType::class)
            ->add('published', TextType::class)
            ->add('author', TextType::class)
            ->add('content', TextType::class)
            ->add('slug', TextType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title', TextType::class)
            ->add('published', TextType::class)
            ->add('author', TextType::class)
            ->add('content', TextType::class)
            ->add('slug', TextType::class);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('title', TextType::class)
            ->add('published', TextType::class)
            ->add('author', TextType::class)
            ->add('content', TextType::class)
            ->add('slug', TextType::class);
    }
}
