<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ResearchRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ResearchRepository extends EntityRepository
{
    public function findByLocalizationTags($localization, $tags)
    {
        $query = "SELECT r FROM AppBundle:Research r JOIN r.tags t WHERE t.name IN (:tags) AND r.localization = :localization";
        return $this->getEntityManager()
            ->createQuery($query)->setParameter('localization', $localization)->setParameter('tags', $tags)->getResult();
    }

    public function findByTags($tags)
    {
        $query = "SELECT r FROM AppBundle:Research r JOIN r.tags t WHERE t.name IN (:tags)";
        return $this->getEntityManager()
            ->createQuery($query)->setParameter('tags', $tags)->getResult();
    }
}
