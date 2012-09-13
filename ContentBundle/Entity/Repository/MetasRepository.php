<?php
namespace CAF\ContentBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class MetasRepository extends EntityRepository
{

	public function getMetas() {
		return $this->getEntityManager()
					->createQueryBuilder('m')
					->Select('m')
					->from('CAFContentBundle:Metas','m')
					->where('m.type=:normal')
					->setParameter('normal', 'normal')
					->orwhere('m.type=:other')
					->setParameter('other', 'other')
					->getQuery()
					->getResult();
	}

}	