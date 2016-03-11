<?php

namespace Nz\WordpressBundle\Repository;

interface RepositoryInterface
{
    public function getQueryBuilder();
    public function getAlias();
}
