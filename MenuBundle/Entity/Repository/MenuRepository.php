<?php
namespace CAF\MenuBundle\Entity\Repository;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class MenuRepository extends NestedTreeRepository
{
	public function getPagination($menu_taxonomy,$nb_elem_page)
	{
    	$nb_elem = $this->getEntityManager()
    					->createQueryBuilder('m')
                        ->Select('count(m)')
                        ->from('CAFMenuBundle:Menu','m')
                        ->where('m.id_menu_taxonomy=:menu_taxonomy')
                        ->setParameter('menu_taxonomy',$menu_taxonomy)
                        ->getQuery()
    					->getSingleResult();
    	$nb_elem = current($nb_elem);
        $nb_elem_page = $nb_elem_page;
        return ceil($nb_elem/$nb_elem_page);
    }

    public function getTreePagination($menu_taxonomy, $page, $nb_elem_page)
    {
    	$offset = ($page-1)*$nb_elem_page;
    	$max = $nb_elem_page;
    	return $this->getEntityManager()
		    		->createQueryBuilder()
				    ->select('node')
				    ->from('CAFMenuBundle:Menu', 'node')
				    ->orderBy('node.root, node.lft', 'ASC')
				    ->where('node.id_menu_taxonomy=:menu_taxonomy')
				    ->setParameter('menu_taxonomy',$menu_taxonomy)
				    ->setFirstResult($offset)
                	->setMaxResults($max)
				    ->getQuery();
    }


    public function getTree($menu_taxonomy)
    {
    	return $this->getEntityManager()
		    		->createQueryBuilder()
				    ->select('node')
				    ->from('CAFMenuBundle:Menu', 'node')
				    ->orderBy('node.root, node.lft', 'ASC')
				    ->where('node.id_menu_taxonomy=:menu_taxonomy')
				    ->setParameter('menu_taxonomy',$menu_taxonomy)
				    ->getQuery();
    }

    public function getTreePublished($menu_taxonomy)
    {
        return $this->getEntityManager()
                    ->createQueryBuilder()
                    ->select('node')
                    ->from('CAFMenuBundle:Menu', 'node')
                    ->orderBy('node.root, node.lft', 'ASC')
                    ->where('node.id_menu_taxonomy=:menu_taxonomy')
                    ->andwhere('node.published=:published')
                    ->setParameter('menu_taxonomy',$menu_taxonomy)
                    ->setParameter('published',1)
                    ->getQuery();
    }

    public function getTreePublishedByAlias($alias)
    {
        return $this->getEntityManager()
                    ->createQueryBuilder()
                    ->select('node')
                    ->from('CAFMenuBundle:Menu', 'node')
                    ->join('node.id_menu_taxonomy','t')
                    ->where('t.alias=:slug')
                    ->andwhere('node.published=:published')
                    ->setParameter('slug',$alias)
                    ->setParameter('published',1)
        ;
    }

    public function getTreePublishedByMenuId($lft, $rgt, $root, $id)
    {
        return $this->getEntityManager()
                    ->createQueryBuilder()
                    ->select('node')
                    ->from('CAFMenuBundle:Menu', 'node')
                    ->where('node.lft > :lft')
                    ->andwhere('node.rgt < :rgt')
                    ->andwhere('node.root = :root')
                    ->andwhere('node.parent = :parent')
                    ->andwhere('node.published=:published')
                    ->setParameter('parent',$id)
                    ->setParameter('lft',$lft)
                    ->setParameter('rgt',$rgt)
                    ->setParameter('root',$root)
                    ->setParameter('published',1)
                    ->getQuery()
        ;
    }

    public function getMenuParent($menu_taxonomy)
    {
    	return $this->getEntityManager()
		    		->createQueryBuilder()
				    ->select('node')
				    ->from('CAFMenuBundle:Menu', 'node')
				    ->orderBy('node.root, node.lft', 'ASC')
				    ->where('node.id_menu_taxonomy=:menu_taxonomy')
				    ->setParameter('menu_taxonomy',$menu_taxonomy)
				    ;
    }

    public function getTranslationMenu()
    {
    	return $this->getEntityManager()
		    		->createQueryBuilder()
				    ->select('trans_ext, menu_ext')
				    ->from('CAFMenuBundle:MenuTranslation', 'trans_ext')
                                    ->join('trans_ext.object', 'menu_ext')
                                    ->groupBy('trans_ext.object')                                 
                                    ->getQuery()
                                    ->getArrayResult();   
                                    ;        
    }    
    
}