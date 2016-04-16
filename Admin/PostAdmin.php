<?php

namespace Nz\WordpressBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Route\RouteCollection;

class PostAdmin extends Admin
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
            $this->trans('sidemenu.link_edit_post'), array('uri' => $admin->generateUrl('edit', array('id' => $id)))
        );

        $menu->addChild(
            $this->trans('sidemenu.post_metas', array(), 'NzWordpressBundle'), array('uri' => $admin->generateUrl('nz.wordpress.admin.posts_metas.list', array('id' => $id)))
        );
        
        $menu->addChild(
            $this->trans('sidemenu.post_taxonomy', array(), 'NzWordpressBundle'), array('uri' => $admin->generateUrl('nz.wordpress.admin.taxonomy.list', array('id' => $id)))
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
            ->addIdentifier('id')
            ->addIdentifier('title')
            ->add('type')
            ->add('status')
            /*->add('slug')*/
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
                ->add('title', 'text')
                /*->add('name')*/
                ->add('slug')
                ->add('excerpt')
                /* ->add('enabled') */
                ->add('content', 'textarea', array(
                    'attr' => array('style' => 'min-height:600px')
                /*->add('content', 'sonata_simple_formatter_type', array(*/
                    /* 'format' => 'markdown' */
                    /*'format' => 'richhtml'*/
                ))
            ->end()
            ->with('System', ['class' => 'col-md-4'])
                /*->add('user')*/
                ->add('user', 'sonata_type_model_list',array(
                    'btn_add' => false,
                    'btn_delete' => false
                ))
                ->add('status')
                ->add('commentStatus')
                ->add('pingStatus')
                ->add('password')
                ->add('guid', null, ['attr'=> ['readonly' => true]])
                ;
                if('attachment' ==$this->getSubject()->getType()){
                    $formMapper
                        ->add('mimeType');

                }
                if('nav_menu_item' ==$this->getSubject()->getType()){
                    $formMapper
                        ->add('menuOrder');

                }
                $formMapper
                ->add('type')
                ->add('commentCount')
                ->add('date', 'sonata_type_datetime_picker', array(
                    'dp_side_by_side' => true,
                    'required' => false,
                ))
                ->add('modifiedDate', 'sonata_type_datetime_picker', array(
                    'dp_side_by_side' => true,
                    'required' => false,
                ))
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $that = $this;

        $datagridMapper
            ->add('title')
            ->add('status')
            ->add('type')
            ->add('content')
/*            
            ->add('type', 'doctrine_orm_callback', array(
//                'callback'   => array($this, 'getWithOpenCommentFilter'),
                'callback' => function($queryBuilder, $alias, $field, $value) {
                    if (!$value['value']) {
                        return;
                    }
                  $posts =  $queryBuilder
                        ->select('o.type')
                        ->from('Nz\WordpressBundle\Entity\Post', '0')
                        ->distinct()
                        ->getQuery()
                        ->getResult()
                    ;
                    return true;
                    
                    $queryBuilder->leftJoin(sprintf('%s.comments', $alias), 'c');
                    $queryBuilder->andWhere('c.status = :status');
                    $queryBuilder->setParameter('status', Comment::STATUS_MODERATE);

                },
                'field_type' => 'checkbox'//choice
            ))
 */
        ;
    }

}
