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

    /**
     * @param $rowQuery
     * @param int $limit
     *
     * @return array
     */
    public function findBySearchQuery($rowQuery, $limit = Post::NUM_ITEMS)
    {
        $query = $this->sanitizeSearchQuery($rowQuery);
        $searchTerms = $this->extractSearchTerms($query);

        if (0 === count($searchTerms)) {
            return [];
        }

        $queryBuilder = $this->createQueryBuilder('p');

        foreach ($searchTerms as $key => $term) {
            $queryBuilder
                ->orWhere('p.title LIKE :t_' . $key)
                ->setParameter('t_' . $key, '%' . $term . '%' );
        }

        return $queryBuilder
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    private function sanitizeSearchQuery($query)
    {
        return preg_replace('/[^[:alnum:] ]/', '', trim(preg_replace('/[[:space:]]+/', ' ', $query)));
        return $query;
    }

    /**
     * @param $searchQuery
     *
     * @return array
     */
    private function extractSearchTerms($searchQuery)
    {
        $terms = array_unique(explode(' ', mb_strtolower($searchQuery)));

        return array_filter($terms, function ($term) {
            return 2 <= mb_strlen($term);
        });
    }
}
