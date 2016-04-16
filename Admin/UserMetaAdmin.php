<?php

namespace Nz\WordpressBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Route\RouteCollection;

class UserMetaAdmin extends Admin
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
            ->add('user')
            ->add('value')
        ;
        if ($this->isChild()) {
            $listMapper->remove('user');
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
            ->end()

        ;
        if (!$this->isChild()) {
            $formMapper
                ->with('General')
                    ->add('user','sonata_type_model_list', array(
                        'required' => false,
                        'btn_add' => false,
                        'btn_delete' => false,
                    ))
                ->end()
            ;
        }
        $formMapper
            ->with('General')
                ->add('key')
                ->add('value')
            ->end()
        ;
    }
}
