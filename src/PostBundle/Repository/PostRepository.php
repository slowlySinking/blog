<?php

namespace PostBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use PostBundle\Entity\Post;

class PostRepository extends EntityRepository
{
    /**
     * @param int $page
     *
     * @return Pagerfanta
     */
    public function findLatest($page = 1)
    {
        $query = $this->createQueryBuilder('p')
            ->join('p.user', 'u')
            ->leftJoin('p.tags', 't')
            ->orderBy('p.createdAt', 'DESC')->getQuery();

        return $this->createPaginator($query, $page);
    }

    /**
     * @param Query $query
     * @param $page
     *
     * @return Pagerfanta
     */
    private function createPaginator(Query $query, $page)
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage(Post::NUM_ITEMS);
        $paginator->setCurrentPage($page);

        return $paginator;
    }
}
