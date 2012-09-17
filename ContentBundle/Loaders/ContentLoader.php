<?php
namespace CAF\ContentBundle\Loaders;

use CAF\ContentBundle\Entity\Content;
use CAF\ContentBundle\Entity\ContentTranslation;
use CAF\ContentBundle\Entity\FieldsValue;
use CAF\ContentBundle\Entity\MetasValue;
use CAF\ContentBundle\Entity\Repository\FieldsValueRepository;
use CAF\ContentBundle\Entity\Repository\MetasValueRepository;
use CAF\ContentBundle\Entity\Repository\FieldsRepository;
use CAF\ContentBundle\Entity\Repository\MetasRepository;
use CAF\ContentBundle\Configuration\ConfigurationUpload;
use CAF\ContentBundle\Entity\ContentTaxonomy;
use Doctrine\DBAL\DriverManager;


class ContentLoader
{
	public function __construct() {

	}

	public function load(Content $content)
	{
		$content->setValuesFr(array());
		$content->setValuesEn(array());
		$content->setValuesDe(array());

		$content->setMetasValuesFr(array());
		$content->setMetasValuesEn(array());
		$content->setMetasValuesDe(array());

		$content->setTranslationFr(new ContentTranslation());
		$content->setTranslationEn(new ContentTranslation());
		$content->setTranslationDe(new ContentTranslation());
	}

	public function loadContent(Content $content, FieldsValueRepository $repository, MetasValueRepository $meta_repository)
	{
		$translations = $content->getTranslations();
		foreach($translations as $t) {
			switch ($t->getLang()->getCode()) {
				case 'fr':
					$fieldsvalue = $repository->getValues($t->getId());
					$metasvalue = $meta_repository->getValues($t->getId());
					$valuesFr = array();
					$metasValuesFr = array();

					foreach ($fieldsvalue as $fieldvalue) {
						if ($fieldvalue->getValue() != '')
							$temp_value = $fieldvalue->getValue();
						else
							$temp_value = '';

						if(is_array($temp_value) && array_key_exists('alt', $temp_value)) {
							$valuesFr[$fieldvalue->getField()->getName()]['alt'] = $temp_value['alt'];
							if(array_key_exists('title', $temp_value)) {
								$valuesFr[$fieldvalue->getField()->getName()]['title'] = $temp_value['title'];
							} else {
								$valuesFr[$fieldvalue->getField()->getName()]['title'] = '';
							}
							if(array_key_exists('image', $temp_value)) {
								$valuesFr[$fieldvalue->getField()->getName()]['image'] = $temp_value['image'];
							} else {
								$valuesFr[$fieldvalue->getField()->getName()]['image'] ='';
							} 
						} else if($fieldvalue->getField()->getIdFieldTaxonomy()->getName() == 'Date' && $temp_value != '') {
							$date_tab = explode('/',$temp_value);
							$valuesFr[$fieldvalue->getField()->getName()] = implode('/',array_reverse($date_tab));	
						} else {
							$valuesFr[$fieldvalue->getField()->getName()] = $temp_value;	
						}
						
					}

					foreach ($metasvalue as $metavalue) {
						$metasValuesFr[$metavalue->getMeta()->getName()] = $metavalue->getValue();
					}

					$content->setTranslationFr($t);
					$content->setMetasValuesFr($metasValuesFr);
					$content->setValuesFr($valuesFr);

					break;
				case 'en':
					$fieldsvalue = $repository->getValues($t->getId());
					$metasvalue = $meta_repository->getValues($t->getId());
					
					$valuesEn = array();
					$metasValuesEn = array();

					foreach ($fieldsvalue as $fieldvalue) {
						if ($fieldvalue->getValue() != '')
							$temp_value = $fieldvalue->getValue();
						else
							$temp_value = '';
						if(is_array($temp_value) && array_key_exists('alt', $temp_value)) {
							$valuesEn[$fieldvalue->getField()->getName()]['alt'] = $temp_value['alt'];
							if(array_key_exists('title', $temp_value)) {
								$valuesEn[$fieldvalue->getField()->getName()]['title'] = $temp_value['title'];
							} else {
								$valuesDe[$fieldvalue->getField()->getName()]['title'] = '';
							}
							if(array_key_exists('image', $temp_value)) {
								$valuesEn[$fieldvalue->getField()->getName()]['image'] = $temp_value['image'];
							} else {
								$valuesEn[$fieldvalue->getField()->getName()]['image'] ='';
							}
						} else {
							$valuesEn[$fieldvalue->getField()->getName()] = $temp_value;	
						}
					}
					
					foreach ($metasvalue as $metavalue) {
						$metasValuesEn[$metavalue->getMeta()->getName()] = $metavalue->getValue();
					}

					$content->setTranslationEn($t);
					$content->setMetasValuesEn($metasValuesEn);
					$content->setValuesEn($valuesEn);
					break;
				case 'de':
					$fieldsvalue = $repository->getValues($t->getId());
					$metasvalue = $meta_repository->getValues($t->getId());
					$valuesDe = array();
					$metasValuesDe = array();

					foreach ($fieldsvalue as $fieldvalue) {
						if ($fieldvalue->getValue() != '')
							$temp_value = $fieldvalue->getValue();
						else
							$temp_value = '';
						if(is_array($temp_value) && array_key_exists('alt', $temp_value)) {
							$valuesDe[$fieldvalue->getField()->getName()]['alt'] = $temp_value['alt'];
							if(array_key_exists('title', $temp_value)) {
								$valuesDe[$fieldvalue->getField()->getName()]['title'] = $temp_value['title'];
							} else {
								$valuesDe[$fieldvalue->getField()->getName()]['title'] = '';
							}
							if(array_key_exists('image', $temp_value)) {
								$valuesDe[$fieldvalue->getField()->getName()]['image'] = $temp_value['image'];
							} else {
								$valuesDe[$fieldvalue->getField()->getName()]['image'] ='';
							}	
						} else {
							$valuesDe[$fieldvalue->getField()->getName()] = $temp_value;	
						}
					}

					foreach ($metasvalue as $metavalue) {
						$metasValuesDe[$metavalue->getMeta()->getName()] = $metavalue->getValue();
					}

					$content->setTranslationDe($t);
					$content->setMetasValuesDe($metasValuesDe);
					$content->setValuesDe($valuesDe);
					break;					 
			}
		}
		
	}

	public function loadContentTranslation($path_upload, $translation, $values, $files, $metasvalues, $lang, $country, MenuRepository $menu_repo, MetasRepository $meta_repo, ContentTaxonomy $content_taxonomy)
	{

		$configUpload = new ConfigurationUpload();

		$fields = $content_taxonomy->getFields();

		foreach($fields as $field) {
			$fieldValue = new FieldsValue();
			if(isset($values[$field->getName()])) {
				if($field->getIdFieldTaxonomy()->getName() == 'Date') {
					$date_raw = $values[$field->getName()];
					$date_tab = explode('/', $date_raw);
					$value = implode('/',array_reverse($date_tab));
				} else {
					$value = $values[$field->getName()];	
				}
			}

			if(isset($files[$field->getName()])) {
				$file = $files[$field->getName()];
				if($field->getIdFieldTaxonomy()->getName() == 'Image') {
					$file = $file['image'];
					$dest = $configUpload->getUploadRootDir($path_upload,$lang,$country,'content');
					$path = $this->upload($file,$dest,$configUpload->getUploadDir($lang,$country,'content'));
					$value['image'] = $path;
				
				} else if ($field->getIdFieldTaxonomy()->getName() == 'File') {
					$dest = $configUpload->getUploadRootDir($path_upload,$lang,$country,'content');
					$path = $this->upload($file,$dest,$configUpload->getUploadDir($lang,$country,'content'));
					$value = $path;	
				}
			}
			$fieldValue->setValue(serialize($value));
			$fieldValue->setField($field);	
			$fieldValue->setContentTranslation($translation);
			$translation->addFieldsValue($fieldValue);
			
		}
		

		foreach($metasvalues as $name=>$value) {
			$meta = $meta_repo->findBy(array('name' => $name));
			$meta = current($meta);
			$id = $meta->getId();
			$metasValue = new MetasValue();
			$metasValue->setMeta($meta);
			$metasValue->setContentTranslation($translation);

			if($name == 'Title' && $value != '') {
				$value = $translation->getTitle();					
			}

			$metasValue->setValue($value);
			$translation->addMetasValue($metasValue);
		}

		return $translation;
	}

	public function updateContentTranslation($path_upload,$translation, $values, $files, $valuesMetas, $lang, $country, FieldsRepository $repository, MetasRepository $meta_repo, ContentTaxonomy $content_taxonomy)
	{

		$configUpload = new ConfigurationUpload();
		$fieldsvalues = $translation->getFieldsValue();
		$metasvalues = $translation->getMetasvalue();
		foreach($fieldsvalues as $fieldValue) {
			$field = $fieldValue->getField();
			$value = '';

			if(isset($values[$field->getName()])) {
				if($field->getIdFieldTaxonomy()->getName() == 'Date') {
					$date_raw = $values[$field->getName()];
					$date_tab = explode('/', $date_raw);
					$value = implode('/',array_reverse($date_tab));
				} else {
					$value = $values[$field->getName()];	
				}
			}

			if(isset($files[$field->getName()])) {
				$file = $files[$field->getName()];
				if($field->getIdFieldTaxonomy()->getName() == 'Image') {
					$file = $file['image'];
					if($file != NULL) {
						$dest = $configUpload->getUploadRootDir($path_upload,$lang,$country,'content');
						$path = $this->upload($file,$dest,$configUpload->getUploadDir($lang,$country,'content'));
						$value['image'] = $path;
					} else {
						$oldvalue = $fieldValue->getValue();
						$value['image'] = $oldvalue['image'];
					}
				} else if ($field->getIdFieldTaxonomy()->getName() == 'File') {
					$dest = $configUpload->getUploadRootDir($path_upload,$lang,$country,'content');
					$path = $this->upload($file,$dest,$configUpload->getUploadDir($lang,$country,'content'));
					$value = $path;	
				}
			}
			$fieldValue->setValue(serialize($value));
			$fieldValue->setField($field);	
			$fieldValue->setContentTranslation($translation);
			$translation->addFieldsValue($fieldValue);

		}
		foreach($metasvalues as $valueMeta) {
			foreach($valuesMetas as $name=>$value) {
				$meta = $meta_repo->findBy(array('name' => $name));
				$meta = current($meta);
				if($valueMeta->getMeta()->getId() == $meta->getId()) {
					$valueMeta->setMeta($meta);
					$valueMeta->setContentTranslation($translation);
					if ($name == 'Title' && $value == '') {
						$value = $translation->getTitle();
					}
					$valueMeta->setValue($value);
					$translation->addMetasValue($valueMeta);
				}
			}
		}
		
		return $translation;
	}


	public function upload($file,$dest,$web_dir)
	{

		if (is_object($file)) {
			$extension = $file->guessExtension();
			$path = uniqid().'.'.$extension;
			$file->move($dest, $path);
			return $web_dir.'/'.$path;
		}
	}

	public function arrayToCollectionCategory(array $tab, $repo)
	{
		$collection = new \Doctrine\Common\Collections\ArrayCollection();
		foreach($tab as $key=>$value) {
			$category = $repo->find($value);
			$collection->add($category);
		}
		return $collection;
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