<?php

namespace Nz\WordpressBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Route\RouteCollection;

class CommentAdmin extends Admin
{

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     *
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('author')
            ->add('post')
            ->add('approved')
            ->add('email')

        ;
    }

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General', ['class' => 'col-md-8'])
                ->add('id', null, ['attr' => ['readonly' => true]])
                ->add('post')
                ->add('parent', 'sonata_type_model', [
                    /*'empty_data' => false,*/
                    /*'data' => 0,*/
                    'required' => false,
                    'btn_add' => false
                    ])
                ->add('author')
                ->add('authorEmail')
                ->add('authorUrl')
                ->add('authorIp')
                ->add('date')
                ->add('content')
                ->add('approved')
                ->add('agent')
            ->end()
        ;
    }
}
