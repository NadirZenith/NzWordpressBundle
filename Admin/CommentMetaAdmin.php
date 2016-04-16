<?php

namespace Nz\WordpressBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Route\RouteCollection;

class CommentMetaAdmin extends Admin
{

    protected $parentAssociationMapping = 'user';

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     *
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {

        $listMapper
            ->addIdentifier('key')
            ->add('comment')
            ->add('value')
        ;
        if ($this->isChild()) {
            $listMapper->remove('comment');
        }
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
                ->add('user','sonata_type_model_list', array(
                    'required' => false,
                    'btn_add' => false,
                    'btn_delete' => false,
                ))
                ->add('key')
                ->add('value')
            ->end()
        ;
        if ($this->isChild()) {
            $formMapper
                ->remove('user')
            ;
        }
        ;
    }
}
