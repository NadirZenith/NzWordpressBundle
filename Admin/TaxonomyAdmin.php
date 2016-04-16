<?php

namespace Nz\WordpressBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Route\RouteCollection;

class TaxonomyAdmin extends Admin
{

    /**
     * {@inheritdoc}
     */
    protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        return;
        if (!$childAdmin && !in_array($action, array('edit'))) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;

        $id = $admin->getRequest()->get('id');

        $menu->addChild(
            $this->trans('sidemenu.link_edit_post'), array('uri' => $admin->generateUrl('edit', array('id' => $id)))
        );

        $menu->addChild(
            $this->trans('sidemenu.post_metas', array(), 'NzShopBundle'), array('uri' => $admin->generateUrl('nz.wordpress.admin.posts_metas.list', array('id' => $id)))
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
            /* ->add('custom', 'string', array('template' => 'AppCmBundle:CRUD:list_custom_image.html.twig', 'label' => 'Artist')) */
            ->addIdentifier('name')
            
            ->add('term')
            ->add('count')
        /* ->add('status') */
        /* ->add('slug') */
        /*
          ->add('enabled', null, ['editable' => true])
          ->add('featured', null, ['editable' => true])
          ->add('_action', 'actions', array(
          'actions' => array(
          'show' => array(),
          'edit' => array(),
          'delete' => array(),
          )
          ))
         */
        ;

        return $listMapper;
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
                ->add('name')
                /* ->add('name') */
                /* ->add('enabled') */
                ->add('description', 'textarea')
                ->add('parent')
                ->add('count')
                ->add('term')
                ->add('posts')
            ->end()
        ;
        
        if($this->isChild()){
            $formMapper->remove('posts');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {

        $datagridMapper
            ->add('name')
            ->add('term')
            ->add('posts')
            ;
             
    }
}
