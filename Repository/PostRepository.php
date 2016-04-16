<?php

namespace Nz\WordpressBundle\Repository;

use Nz\WordpressBundle\Entity\Post;

class PostRepository extends AbstractRepository
{

    public function findAttachmentsByPost(Post $post)
    {
        return $this->getQueryBuilder()
                ->andWhere('p.type = :type AND p.parent = :post')
                ->setParameter('type', 'attachment')
                ->setParameter('post', $post)
                ->getQuery()
                ->getResult()
        ;
    }

    public function findAttachmentById($id)
    {
        return $this->getQueryBuilder()
                ->where('p.type = :type AND p.id = :id')
                ->setParameter('type', 'attachment')
                ->setParameter('post', $id)
                ->getQuery()
                ->getResult()
        ;
    }

    public function findByType($type, $status = 'publish')
    {
        $qb = $this->getQueryBuilder();

        return $this->getByTypeBuilder($type, $status)
                /* ->setFirstResult(11) */
                /* ->setMaxResults(350) */
                ->getQuery()
                ->getResult()
        ;
    }

    public function getByTypeBuilder($type, $status = 'publish')
    {
        $qb = $this->getQueryBuilder();
        return $qb
                ->where($qb->expr()->like('p.type', ':type'))
                ->setParameter('type', $type)
                ->andWhere($qb->expr()->like('p.status', ':status'))
                ->setParameter('status', $status)
        ;
    }

    public function getAlias()
    {
        return 'p';
    }
}
