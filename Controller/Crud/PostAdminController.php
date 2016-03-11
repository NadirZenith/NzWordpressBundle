<?php

namespace Nz\WordpressBundle\Controller\Crud;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Media\Media;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Nz\Wp\MigrationBundle\Admin\Traits\MigrationTrait;

class PostAdminController extends Controller
{

    use MigrationTrait;
    const WP_UPLOADS_BASE = '/media/tino/data/sites/www/clubber-mag-dev/wp-content/uploads/';

    /**
     * Default action needed for sonata-admin menu builder to show in menu
     */
    public function listAction2(Request $request = null)
    {

        if (!$request->get('filter')) {
            return new RedirectResponse($this->admin->generateUrl('list-posts'));
        }

        $datagrid = $this->admin->getDatagrid();
        /* dd($datagrid); */
        $rep = $this->getWpPostRepository();
        $posts = $rep->findBy([
            'type' => 'artist',
            'status' => 'publish',
        ]);


        return $this->render('NzWpMigrationBundle:CRUD:home.html.twig', array(
                'action' => 'home',
                'dump' => $posts,
                'datagrid' => $datagrid,
        ));
    }

    public function ListPostsAction(Request $request = null)
    {
        /* dd($this->admin->getTemplate('pager_links')); */
        /*
          dd(
          $this->admin->getTemplate('list')
          );

          return parent::listAction($request);

          $this->admin->setTemplate('list', 'NzWpMigrationBundle:CRUD:home.html.twig');
         */
        $this->admin->setTemplate('layout', 'NzWpMigrationBundle:CRUD:home.html.twig');


        $datagrid = $this->admin->getDatagrid();
        $formView = $datagrid->getForm()->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($formView, $this->admin->getFilterTheme());

        return $this->render($this->admin->getTemplate('list'), array(
                'action' => 'list-posts',
                'form' => $formView,
                'datagrid' => $datagrid,
                'csrf_token' => $this->getCsrfToken('sonata.batch'),
                ), null, $request);
    }

    public function UsersAction(Request $request = null)
    {
        $em = $this->getWpEntityManager();
        /*
          $query = $em->createQuery('SELECT u FROM Nz\WordpressBundle\Entity\User u');
          $users = $query->getResult();
         */

        $query = $em->createQueryBuilder()
            ->select('u')
            ->from('Nz\WordpressBundle\Entity\User', 'u')
            /* ->setMaxResults(80) */
            ->getQuery();

        $users = $query->getResult();

        $excluded_metas = [
            'wpcf-group-form-toggle', 'users_per_page', 'use_ssl', 'upload_per_page', 'screen_layout_post', 'rich_editing', 'session_tokens',
            'nav_menu_recently_edited', 'show_admin_bar_front', 'show_welcome_panel', 'meta-box-order_post', 'managenav-menuscolumnshidden', 'last_login_time',
            'dismissed_wp_pointers', 'edit_agenda_per_page', 'comment_shortcuts', 'admin_color', '_yoast_wpseo_profile_updated', 'entry_id', 'googleplus'
        ];

        $nots_like = [
            'closedpostboxes%',
            'metaboxhidden%',
            '_wp%',
            'wp_%',
            'wpseo%',
            'wppb%',
        ];

        $qb = $em->createQueryBuilder();
        $qb
            ->select('m.key')
            ->from('Nz\WordpressBundle\Entity\UserMeta', 'm')
            /* ->setMaxResults(10) */
            ->groupBy('m.key')
            ->orderBy('m.key', 'ASC')
            ->distinct()
        ;
        $this->buildQbWhere($qb, $nots_like);
        $qb->andWhere($qb->expr()->notIn('m.key', $excluded_metas));

        $metas = $qb->getQuery()->getResult();

        return $this->render('NzWpMigrationBundle:CRUD:users.html.twig', array(
                'action' => 'users',
                'users' => $users,
                'metas' => $metas,
        ));
    }

    public function postTypesAction(Request $request = null)
    {
        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }
        $em = $this->getWpEntityManager();

        $excluded_types = ['acf', 'nav_menu_item', 'revision', 'spucpt', 'wp-types-group', 'ml-slider'];
        $qb = $em->createQueryBuilder();
        $types = $qb
            ->select('p.type')
            ->from('Nz\WordpressBundle\Entity\Post', 'p')
            ->where($qb->expr()->notIn('p.type', $excluded_types))
            ->distinct()
            ->getQuery()
            ->getResult();

        $excluded_metas = ['rule', 'spu_options', 'spu_rules', 'position',
            'enclosure', 'field_5332ac9bbfb6f',
            'nzwpcm_ticketscript_event_id',
            /* '_photo-gallery', 'photo-gallery' */
        ];

        $nots_like = [
            '_wp%',
            '_edit%',
            '_menu%',
            '_yoast%',
            '_gform%',
            'ml-slider%',
            '_oembed%',
            'wpcf-event_flyer_bac%',
        ];

        $qb = $em->createQueryBuilder();
        $qb
            ->select('m.key')
            ->from('Nz\WordpressBundle\Entity\PostMeta', 'm')
            ->groupBy('m.key')
            ->orderBy('m.key', 'ASC')
            /* ->setMaxResults(150) */
            /* ->groupBy('pm.key') */
            ->distinct();
        $this->buildQbWhere($qb, $nots_like);

        $qb->andWhere($qb->expr()->notIn('m.key', $excluded_metas));

        $metas = $qb->getQuery()->getResult();

        return $this->render('NzWpMigrationBundle:CRUD:post_types.html.twig', array(
                'action' => 'post-types',
                'types' => $types,
                'metas' => $metas,
        ));
    }

    private function buildQbWhere($qb, $conditions)
    {
        foreach ($conditions as $key => $v) {
            /* $qb->andWhere(sprintf('pm.key NOT LIKE ?%d', $key)); */
            $qb->andWhere($qb->expr()->notLike('m.key', sprintf('?%d', $key)));
        }
        $qb->setParameters($conditions);
    }

    /**
     * Artists process action
     */
    public function artistsAction(Request $request = null)
    {
        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }

        $rep = $this->getWpPostRepository();

        $posts = $rep->findBy([
            'type' => 'artist',
            'status' => 'publish'
            ], []);
        /* ], [], 10); */

        /* array_walk($posts, [$this, 'migrateArtist']); */
        /* $em = $this->getDoctrine()->getManager(); */

        $artists = [];
        $errors = [];
        set_time_limit(0);
        /* ini_set('memory_limit','16M'); */
        ini_set('memory_limit', '-1');
        foreach ($posts as $post) {
            $em = $this->getEntityManager();
            $artist = $this->migrateArtist($post);
            $em->persist($artist);

            try {
                $em->flush();
                $artists[] = $artist;
            } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $ex) {

                $errors[] = $artist;
            }
        }



        d($posts);
        d($errors);
        dd($artists);

        /*
          return $this->render('NzWpMigrationBundle:CRUD:home.html.twig', array(
          'action' => 'artists',
          'dump' => [$posts, $artists],
          ));
         */
    }

    protected function migrateArtist($post)
    {

        $artist = new \App\CmBundle\Entity\Music\Artist();
        $artist->setEnabled(true);
        $artist->setName($post->getTitle());
        $artist->setWpId($post->getId());
        $artist->setCreatedAt($post->getDate());
        $artist->setContent($this->processContent($post->getContent()));

        $metas = $this->filterMetas($post->getMetas());

        if (isset($metas['_thumbnail_id'])) {
            $artist->setImage($this->getMediaImageFromWpThumbId($metas['_thumbnail_id'], 'artist'));
            unset($metas['_thumbnail_id']);
        }
        dd($metas);

        /* d($this->get('security.token_storage')->getToken()->getUser()); */

        return $artist;
    }

    protected function getMediaImageFromWpThumbId($thumb_id, $context = 'default')
    {

        $post_image = $this->getWpPostRepository()->find($thumb_id);

        $metas = $this->filterMetas($post_image->getMetas());

        $img_info = array_pop($metas);

        if (!isset($img_info['file'])) {
            return;
        }

        $media = new Media();
        $media->setBinaryContent(self::WP_UPLOADS_BASE . $img_info['file']);
        $media->setContext($context);
        $media->setProviderName('sonata.media.provider.image');
        $media->setCategory($this->get('sonata.classification.manager.category')->find(1));

        return $media;
    }

    /**
     *  Get entity manager
     * 
     *  @return \Doctrine\ORM\EntityManager Entity Manager
     */
    protected function getEntityManager()
    {
        if (!$this->get('doctrine')->getManager()->isOpen()) {
            $this->get('doctrine')->resetManager();
        }

        return $this->get('doctrine')->getManager();
    }

    /**
     *  Get entity manager
     * 
     *  @return \Doctrine\ORM\EntityManager Entity Manager
     */
    private function getWpPostRepository()
    {
        /* return $this->getWpEntityManager(); */
        $em = $this->getWpEntityManager();

        return $em->getRepository('NzWordpressBundle:Post');
    }

    /**
     *  Get entity manager
     * 
     *  @return \Doctrine\ORM\EntityManager Entity Manager
     */
    private function getWpEntityManager()
    {
        $entityManagerName = $this->getParameter('kayue_wordpress.entity_manager');

        return $this->get('doctrine.orm.' . $entityManagerName . '_entity_manager');
    }
}
