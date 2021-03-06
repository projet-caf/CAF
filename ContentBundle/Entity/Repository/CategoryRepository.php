<?php

namespace CAF\ContentBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;


/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends NestedTreeRepository
{

    public function findAllOrder($page, $nb_elem)
    {

        $offset = ($page-1)*$nb_elem;
        if($offset > 0)
            $offset--;
        else
            $offset = 0;
        
        $max = $nb_elem*3;
        return $this->getEntityManager()
                    ->createQueryBuilder('c,t')
                    ->Select('c,t')
                    ->from('CAFContentBundle:Category', 'c')
                    ->leftjoin('c.translations', 't')
                    ->orderby('c.root, c.lft', 'ASC')
                    ->setFirstResult($offset)
                    ->setMaxResults($max)
                    ->getQuery()
                    ->getResult();
    }

    public function getPagination($nb_elem_page) {
        $nb_elem = $this->getEntityManager()
                    ->createQueryBuilder('c,t')
                    ->Select('count(c)')
                    ->from('CAFContentBundle:Category','c')
                    ->leftjoin('c.translations', 't')
                    ->orderby('c.root, c.lft', 'ASC')
                    ->getQuery()
                    ->getSingleResult();
        $nb_elem = current($nb_elem);
        $nb_elem_page = $nb_elem_page*3;
        return ceil($nb_elem/$nb_elem_page);
    }

    public function getCategoryParent() {
        return $this->getEntityManager()
                    ->createQueryBuilder('c,t')
                    ->Select('c,t')
                    ->from('CAFContentBundle:Category','c')
                    ->leftjoin('c.translations','t')
                    ->orderby('c.root, c.lft', 'ASC');
    }

    public function getCategoryTranslation($id_cat,$lang)
    {
        return $this->getEntityManager()
                    ->createQueryBuilder('c,t')
                    ->Select('t.id')
                    ->from('CAFContentBundle:Category', 'c')
                    ->join('c.translations','t')
                    ->where('t.lang=:lang')
                    ->andwhere('c.id=:id')
                    ->setParameters(array('lang' => $lang, 'id' => $id_cat))
                    ->getQuery()
                    ->getSingleResult();
    }


	public function getAllOrderByTranslation()
    {
		return $this->getEntityManager()
                    ->createQueryBuilder('c,t')
                    ->Select('c,t')
                    ->from('CAFContentBundle:Category','c')
                    ->leftjoin('c.translations', 't')
                    ->join('t.lang','l')
                    ->where('l.code=:lang')
                    ->orderby('t.title', 'DESC')
                    ->setParameter('lang','fr')
                    ->getQuery()
                    ->getResult();
	}

    public function findAllOrderedByLft(){
        return $this->getEntityManager()
                    ->createQueryBuilder('c')
                    ->Select('c')
                    ->from('CAFContentBundle:Category', 'c')
                    ->orderby('c.lft', 'ASC')
                    ->getQuery()
                    ->getResult();
    }	

	public function isFirst()
    {
        return $this->getEntityManager()
                    ->createQueryBuilder('c')
                    ->Select('count(c)')
                    ->from('CAFContentBundle:Category', 'c')
                    ->getQuery()
                    ->getSingleResult();
    }

    public function getTreePublishedByCategoryId($lft, $rgt, $root, $id) {
        return $this->getEntityManager()
                    ->createQueryBuilder()
                    ->select('node')
                    ->from('CAFContentBundle:Content', 'node')
                    ->orderBy('node.root, node.lft', 'ASC')
                    ->where('node.lft>=:lft')
                    ->setParameter('lft',$lft)
                    ->andWhere('node.rgt<=:rgt')
                    ->setParameter('rgt',$rgt)
                    ->andWhere('node.root=:root')
                    ->setParameter('root',$root)
                    ->andWhere('node.id!=:id')
                    ->setParameter('id',$id)
                    ->getQuery();
    }

    public function getCanonical($id) {
        return $this->getEntityManager()
                    ->createQueryBuilder('mv')
                    ->Select('mv.value')
                    ->from('CAFContentBundle:MetasValueCategory','mv')
                    ->leftjoin('mv.category_translation','c')
                    ->leftjoin('mv.meta','m')
                    ->where('c.id=:id')
                    ->setParameter('id',$id)
                    ->andwhere('m.name=:name')
                    ->orWhere('m.name=:canonical')
                    ->setParameter('name','Url')
                    ->setParameter('canonical','Canonical')
                    ->getQuery()
                    ->getResult();
    }

    public function getCategoryLang($lang) {
        return $this->getEntityManager()
                    ->createQueryBuilder('ct')
                    ->Select('ct')
                    ->from('CAFContentBundle:CategoryTranslation','ct')
                    ->where('lang=:lang')
                    ->setParameter('lang',$lang)
        ;            
    }

}