<?php
namespace CAF\ContentBundle\Loaders;

use CAF\ContentBundle\Entity\Repository\CategoryRepository;
use CAF\ContentBundle\Entity\Repository\MetasRepository;
use CAF\ContentBundle\Entity\Repository\MetasValueCategoryRepository;

use CAF\ContentBundle\Entity\Category;
use CAF\ContentBundle\Entity\CategoryTranslation;
use CAF\ContentBundle\Entity\MetasValueCategory;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class CategoryLoader
{
	
	private $repository;

	public function __construct(NestedTreeRepository $repository)
	{
		$this->repository = $repository;
	}

	public function load($category) {

		$category->setMetasValuesFr(array());
		$category->setMetasValuesEn(array());
		$category->setMetasValuesDe(array());

		$category->setTranslationFr(new CategoryTranslation());
		$category->setTranslationEn(new CategoryTranslation());
		$category->setTranslationDe(new CategoryTranslation());
	}

	public function loadCategory(Category $category, MetasValueCategoryRepository $meta_repository)
	{
		$translations = $category->getTranslations();
		foreach($translations as $t) {
			switch ($t->getLang()->getCode()) {
				case 'fr':
					$metasvalue = $meta_repository->getValues($t->getId());
					$metasValuesFr = array();

					foreach ($metasvalue as $metavalue) {
						$metasValuesFr[$metavalue->getMeta()->getName()] = $metavalue->getValue();
					}

					$category->setTranslationFr($t);
					$category->setMetasValuesFr($metasValuesFr);
					
					
					break;
				case 'en':
					$metasvalue = $meta_repository->getValues($t->getId());
					$metasValuesEn = array();


					foreach ($metasvalue as $metavalue) {
						$metasValuesEn[$metavalue->getMeta()->getName()] = $metavalue->getValue();
					}

					$category->setTranslationEn($t);
					$category->setMetasValuesEn($metasValuesEn);

					break;
				case 'de':
					$metasvalue = $meta_repository->getValues($t->getId());
					$metasValuesDe = array();


					foreach ($metasvalue as $metavalue) {
						$metasValuesDe[$metavalue->getMeta()->getName()] = $metavalue->getValue();
					}

					$category->setTranslationDe($t);
					$category->setMetasValuesDe($metasValuesDe);

					break;
			}
		}
		
	}

	public function loadCategoryTranslation($translation, $metasvalues, MetasRepository $meta_repo)
	{

		foreach($metasvalues as $name=>$value) {
			$meta = $meta_repo->findBy(array('name' => $name));
			$meta = current($meta);
			$id = $meta->getId();
			$metasValue = new MetasValueCategory();
			$metasValue->setMeta($meta);
			$metasValue->setCategoryTranslation($translation);
			
			if ($name == 'Title' && $value == '')
				$value = $translation->getTitle();

			$metasValue->setValue(serialize($value));
			$translation->addMetasValueCategory($metasValue);
		}
		return $translation;
	}

	public function updateCategoryTranslation($translation, $valuesMetas, MetasRepository $meta_repo)
	{

		$metasvalues = $translation->getMetasvalue();

		foreach($metasvalues as $valueMeta) {
			foreach($valuesMetas as $name=>$value) {
				$meta = $meta_repo->findBy(array('name' => $name));
				$meta = current($meta);
				if($valueMeta->getMeta()->getId() == $meta->getId()) {
					$valueMeta->setMeta($meta);
					$valueMeta->setCategoryTranslation($translation);
					if ($name == 'Title' && $value == '')
						$value = $translation->getTitle();
					$valueMeta->setValue(serialize($value));
					$translation->addMetasValueCategory($valueMeta);
				}
			}
		}
		return $translation;
	}

	public function getCanonicals($translations) {
		$canonicals = array();
		foreach($translations as $t) {
			$metavalues = $t->getMetasvalue();
			foreach($metavalues as $value) {
				if($value->getMeta()->getName()=='Canonical') {
					if($value->getValue() != '') {
						$canonicals[$t->getLang()->getCode()] = $value->getValue();
					} else {
						$canonicals[$t->getLang()->getCode()] = $t->getAlias();
					}	
				}
			}
		}
		return $canonicals;
	}

}