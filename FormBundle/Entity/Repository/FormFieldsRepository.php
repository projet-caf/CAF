<?php
namespace CAF\FormBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class FormFieldsRepository extends EntityRepository
{

	public function findAllAndType()
	{
		return $this->getEntityManager()
					->createQueryBuilder('f, ft')
					->Select('f, ft')
					->from('CAFFormBundle:FormFields','f')
					->leftjoin('f.id_form_field_taxonomy', 'ft')
					->orderBy('f.ordre','ASC')
					->getQuery()
					->getResult();
	}

	public function getFormFields($taxonomy) {
		
		$qb = $this->getEntityManager()
				   ->createQueryBuilder('f, ft');

		$qb->select('f,ft')
		 ->from('CAFFormBundle:FormFields','f')
		 ->join('f.form_taxonomies','fct')
		 ->join('f.id_form_field_taxonomy','ft')
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
					->from('CAFFormBundle:FormFields', 'f')
					->orderBy('f.ordre','DESC')
					->setMaxResults(1)
					->getQuery()
					->getOneOrNullResult();
	}

	public function decaler($ordre) {
		$this->getEntityManager()
			 ->createQueryBuilder('f')
			 ->update('CAFFormBundle:FormFields', 'f')
			 ->set('f.ordre','f.ordre+1')
			 ->where('f.ordre >= :ordre')
			 ->setParameter('ordre',$ordre)
			 ->getQuery()
			 ->execute()
		;
	}
}