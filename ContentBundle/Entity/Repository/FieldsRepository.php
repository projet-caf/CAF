<?php
namespace CAF\ContentBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class FieldsRepository extends EntityRepository
{

	public function findAllAndType()
	{
		return $this->getEntityManager()
					->createQueryBuilder('f, ft')
					->Select('f, ft')
					->from('CAFContentBundle:Fields','f')
					->leftjoin('f.id_field_taxonomy', 'ft')
					->orderBy('f.ordre','ASC')
					->getQuery()
					->getResult();
	}

	public function getFields($taxonomy) {
		
		$qb = $this->getEntityManager()
				   ->createQueryBuilder('f, ft');

		$qb->select('f,ft')
		 ->from('CAFContentBundle:Fields','f')
		 ->join('f.content_taxonomies','fct')
		 ->join('f.id_field_taxonomy','ft')
		 ->where("fct.id = :taxonomy AND f.published=:published")
		 ->orderBy('f.ordre', 'ASC')
		 ->setParameters(array('taxonomy' => $taxonomy, 'published' => 1));

		$query = $qb->getQuery();               
		$fields = $query->getResult();
		return $fields;
	}

	public function lastOrdre() {
		return $this->getEntityManager()
					->createQueryBuilder('f')		
					->Select('f')
					->from('CAFContentBundle:Fields', 'f')
					->orderBy('f.ordre','DESC')
					->setMaxResults(1)
					->getQuery()
					->getSingleResult();
	}

	public function decaler($ordre) {
		$this->getEntityManager()
			 ->createQueryBuilder('f')
			 ->update('CAFContentBundle:Fields', 'f')
			 ->set('f.ordre','f.ordre+1')
			 ->where('f.ordre >= :ordre')
			 ->setParameter('ordre',$ordre)
			 ->getQuery()
			 ->execute()
		;
	}
}