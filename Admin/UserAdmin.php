<?php

namespace Nz\WordpressBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Route\RouteCollection;

class UserAdmin extends Admin
{

    /**
     * {@inheritdoc}
     */
    protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        if (!$childAdmin && !in_array($action, array('edit'))) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;

        $id = $admin->getRequest()->get('id');
        
        $menu->addChild(
            $this->trans('sidemenu.link_edit_user'), array('uri' => $admin->generateUrl('edit', array('id' => $id)))
        );
        $menu->addChild(
            $this->trans('sidemenu.user_metas', array(), 'NzShopBundle'), array('uri' => $admin->generateUrl('nz_wordpress.admin.user_meta.list', array('id' => $id)))
        );
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     *
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('username')
            /* ->add('username') */
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
            /*
             */
            ->with('General', ['class' => 'col-md-8'])
                ->add('id', null, ['attr' => ['readonly' => true]])
                ->add('displayName')
                ->add('username')
                ->add('password')
                ->add('nicename')
                ->add('email')
                ->add('url')
            ->end()
            ->with('System', ['class' => 'col-md-4'])
                ->add('status', 'sonata_type_boolean')
                ->add('registeredDate', 'sonata_type_datetime_picker', array(
                    'dp_side_by_side' => true,
                    'required' => false,
                ))
                ->add('activationKey')
                ->add('roles', 'collection')
            ->end()
            ->with('Metas', ['class' => 'col-md-8'])
            /*
              ->add('metas', 'sonata_type_immutable_array', array(
              'keys' => array(
              array('ttl',        'text', array('required' => false)),
              array('redirect',   'url',  array('required' => true)),
              )
              ))
             */
            /* ->add('metas', 'wp_metas') */
            ->end()
        ;
    }
}
