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

    public function getAlias()
    {
        return 'p';
    }
}
