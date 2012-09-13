<?php

namespace CAF\FormBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FormFieldsValueRepository extends EntityRepository
{
	public function getValues()
	{
		$results = $this->getEntityManager()
					->createQueryBuilder('fv,f')
					->Select('fv,f')
					->from('CAFFormBundle:FormFieldsValue','fv')
					->join('fv.formfield','f')
					->getQuery()
					->getResult();
		return $results;			
	}

}