<?php

namespace CAF\FormBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;


/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FormTaxonomyRepository extends EntityRepository
{

    public function findAllOrder($page, $nb_elem)
    {

        $offset = ($page-1)*$nb_elem;
        if($offset > 0)
            $offset--;
        else
            $offset = 0;
        
        $max = $nb_elem;
        return $this->getEntityManager()
                    ->createQueryBuilder('c')
                    ->Select('c')
                    ->from('CAFFormBundle:FormTaxonomy', 'c')
                    ->setFirstResult($offset)
                    ->setMaxResults($max)
                    ->getQuery()
                    ->getResult();
    }

    public function getPagination($nb_elem_page) {
        $nb_elem = $this->getEntityManager()
                    ->createQueryBuilder('c')
                    ->Select('count(c)')
                    ->from('CAFFormBundle:FormTaxonomy','c')
                    ->getQuery()
                    ->getSingleResult();
        $nb_elem = current($nb_elem);
        $nb_elem_page = $nb_elem_page;
        return ceil($nb_elem/$nb_elem_page);
    }
}