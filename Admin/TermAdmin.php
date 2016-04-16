<?php

namespace Nz\WordpressBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Route\RouteCollection;

class TermAdmin extends Admin
{

    /**
     * {@inheritdoc}
     */
    protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        return;
       
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
            /*->addIdentifier('id')*/
            ->addIdentifier('name')
            ->add('slug')
            ->add('taxonomy')
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
        $em = $this->getModelManager()->getEntityManager(\Nz\WordpressBundle\Entity\Taxonomy::class);
        
        $qb = $em->createQueryBuilder()
            ->select('t.name')
            ->from(\Nz\WordpressBundle\Entity\Taxonomy::class, 't')
            ->distinct()
            /*->getQuery()*/
            /*->getResult()*/
            ;
        $formMapper
            ->with('General', ['class' => 'col-md-8'])
                ->add('name')
                ->add('slug')
                ->add('group')
                ->add('taxonomy', 'sonata_type_model', array(
                    /*'query' => $qb*/
                ))
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {

        $datagridMapper
            ->add('name')
            ->add('slug')
            ->add('taxonomy')
        ;
    }
}
