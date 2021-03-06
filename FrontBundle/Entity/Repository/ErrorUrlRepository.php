<?php

namespace CAF\FrontBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ErrorUrlRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ErrorUrlRepository extends EntityRepository
{
	public function findByUrl($url) {
		return $this->getEntityManager()
					->createQueryBuilder('eu')
					->Select('eu')
					->from('CAFFrontBundle:ErrorUrl','eu')
					->where('eu.url = :url')
					->setParameter('url',$url)
					->getQuery()
					->getResult();
	}
}	