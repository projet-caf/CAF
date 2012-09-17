<?php

namespace CAF\BlocBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BlocRepository extends EntityRepository
{


	public function findAllOrder($page,$nb_elem = 20)
    {
        $offset = ($page-1)*$nb_elem;
        if($offset < 0)
        	$offset=0;
        $max = $nb_elem;
        return $this->getEntityManager()
					->createQueryBuilder('b')
					->Select('b')
					->from('CAFBlocBundle:Bloc','b')
					->setFirstResult($offset)
					->setMaxResults($max)
					->orderBy('b.position, b.ordre')
					->getQuery()
					->getArrayResult();
    }

    public function getPagination($nb_elem_page) {
    	$nb_elem = $this->getEntityManager()
					->createQueryBuilder('b')
					->Select('count(b)')
					->from('CAFBlocBundle:Bloc','b')
					->getQuery()
					->getSingleResult();
    	$nb_elem = current($nb_elem);
    	return ceil($nb_elem/$nb_elem_page);
    }

    public function getMaxOrdre($position){
    	return $this->getEntityManager()
					->createQueryBuilder('b')
					->Select('MAX(b.ordre)')
					->from('CAFBlocBundle:Bloc','b')
					->where('b.position=:position')
					->setParameter('position', $position)
					->getQuery()
					->getSingleResult();
    }

    public function getBlocBase($position, $cat_id, $item_id) {
    	if($item_id != null){

    		return $this->getEntityManager()
					->createQueryBuilder('b')
					->Select('b')
					->from('CAFBlocBundle:Bloc','b')
					->leftjoin('b.contents_fr', 'con')
					->where('con.id =:item_id')
					->andWhere('b.position =:position')
					->andWhere('b.published =:published')
					->setParameter('item_id', $item_id)
					->setParameter('position', $position)
					->setParameter('published', 1)
					->orderBy('b.position, b.ordre')
					->getQuery()
					->getResult();

		}else{	

    		return $this->getEntityManager()
					->createQueryBuilder('b')
					->Select('b')
					->from('CAFBlocBundle:Bloc','b')
					->leftjoin('b.categories_fr', 'cat')
					->where('cat.id =:cat_id')
					->andWhere('b.position =:position')
					->andWhere('b.published =:published')
					->setParameter('cat_id', $cat_id)
					->setParameter('position', $position)
					->setParameter('published', 1)
					->orderBy('b.position, b.ordre')
					->getQuery()
					->getResult();

		}
    }

    public function getBlocBaseItem($position, $cats, $item_id,$lang='fr') {
    		return $this->getEntityManager()
					->createQueryBuilder('b')
					->Select('b')
					->from('CAFBlocBundle:Bloc','b')
					->leftjoin('b.categories_'.$lang, 'cat')
					->leftjoin('b.contents_'.$lang, 'con')
					->where('con.id =:item_id')
					->orWhere('cat.id in (:cats)')
					->setParameter('cats', $cats)
					->andWhere('b.position =:position')
					->andWhere('b.published =:published')
					->setParameter('item_id', $item_id)
					->setParameter('position', $position)
					->setParameter('published', 1)
					->orderBy('b.position, b.ordre')
					->getQuery()
					->getResult();
	}
					
	public function getBlocBaseCategory($position, $cat_id,$lang='fr') {

    		return $this->getEntityManager()
					->createQueryBuilder('b')
					->Select('b')
					->from('CAFBlocBundle:Bloc','b')
					->leftjoin('b.categories_'.$lang, 'cat')
					->where('cat.id =:cat_id')
					->andWhere('b.position =:position')
					->andWhere('b.published =:published')
					->setParameter('cat_id', $cat_id)
					->setParameter('position', $position)
					->setParameter('published', 1)
					->orderBy('b.position, b.ordre')
					->getQuery()
					->getResult();
    }

    public function getBlocBaseDefault($position){
    	return $this->getEntityManager()
					->createQueryBuilder('b')
					->Select('b')
					->from('CAFBlocBundle:Bloc','b')
					->where('b.position =:position')
					->andWhere('b.published =:published')
					->andWhere('b.all_published=:all')
					->setParameter('all',1)
					->setParameter('published', 1)
					->setParameter('position', $position)
					->orderBy('b.position, b.ordre')
					->getQuery()
					->getResult();
    }

    public function getBlocBaseOrdre($ordre, $position){
    	return $this->getEntityManager()
					->createQueryBuilder('b')
					->Select('b')
					->from('CAFBlocBundle:Bloc','b')
					->where('b.ordre =:ordre')
					->andWhere('b.position =:position')
					->setParameter('ordre', $ordre)
					->setParameter('position', $position)
					->getQuery()
					->getSingleResult();
    }

    public function moveUp($id){
    	$bloc = $this->find($id);
    	$ordre = $bloc->getOrdre();
    	$position = $bloc->getPosition();
    	$ordre_func = $ordre - 1;
    	$bloc_change = $this->getBlocBaseOrdre($ordre_func, $position);
    	
    	$bloc_change->setOrdre($ordre);
    	$ordre --;
    	$bloc->setOrdre($ordre);

    	$em = $this->getEntityManager();
    	$em->persist($bloc);
    	$em->persist($bloc_change);
    	$em->flush();

    	return true;
    }

    public function moveDown($id){
    	$bloc = $this->find($id);
    	$ordre = $bloc->getOrdre();
    	$position = $bloc->getPosition();
    	$ordre_func = $ordre + 1;
    	$bloc_change = $this->getBlocBaseOrdre($ordre_func, $position);
    	
    	$bloc_change->setOrdre($ordre);
    	$ordre ++;
    	$bloc->setOrdre($ordre);

    	$em = $this->getEntityManager();
    	$em->persist($bloc);
    	$em->persist($bloc_change);
    	$em->flush();

    	return true;
    }

    public function getRemoveBloc($ordre){ 
     	return $this->getEntityManager()
                    ->createQueryBuilder('b')
                    ->Select('b')
                    ->from('CAFBlocBundle:Bloc','b')
                    ->where('b.ordre >:ordre')
                    ->andWhere('b.position >:ordre')
                    ->setParameter('ordre', $ordre)                    
                    ->getQuery()
                    ->getResult();      
    
   } 
}