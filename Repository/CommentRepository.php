<?php

namespace Nz\WordpressBundle\Repository;

use Nz\WordpressBundle\Entity\Post;

class CommentRepository extends AbstractRepository
{
    public function findApproved(Post $post)
    {
        return $this->getEntityManager()->getRepository($this->getEntityName())->findBy([
            'post'     => $post,
            'approved' => 1,
            'type'     => '',
        ]);
    }

    public function getAlias()
    {
        return 'c';
    }
}
