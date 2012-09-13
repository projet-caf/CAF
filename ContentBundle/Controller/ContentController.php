<?php

namespace CAF\ContentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Collections\ArrayCollection;

use CAF\ContentBundle\Entity\Content;
use CAF\ContentBundle\Entity\ContentTaxonomy;
use CAF\ContentBundle\Entity\ContentTranslation;
use CAF\ContentBundle\Entity\FieldsValue;
use CAF\ContentBundle\Entity\Metas;
use CAF\ContentBundle\Entity\MetasValue;
use CAF\ContentBundle\Form\ContentType;
use CAF\ContentBundle\Form\ContentTaxonomyPopupType;
use CAF\ContentBundle\Form\MetasType;

use CAF\ContentBundle\Loaders\ContentLoader;
use CAF\ContentBundle\Configuration\ConfigurationUpload;

use Doctrine\DBAL\DriverManager;


/**
 * @Route("/content")
 */
class ContentController extends Controller
{

	/**
	 * @Route("/contents/{page}/{nb_elem}/{taxonomy}", name="content", defaults={"page"="1", "nb_elem"="-1", "taxonomy"="-1"})
	 * @Template()
	 */
	public function indexAction($page,$nb_elem,$taxonomy)
	{	
		if($nb_elem > 0) {
			$contents = $this->getDoctrine()
						->getRepository('CAFContentBundle:Content')
						->findAllOrder($page,$nb_elem,$taxonomy);
		} else {
			$contents = $this->getDoctrine()
						->getRepository('CAFContentBundle:Content')
						->findAllOrder($page,-1,$taxonomy);
		}
		$content_taxonomy = $this->getDoctrine()
								 ->getRepository('CAFContentBundle:ContentTaxonomy')
								 ->findAll();			
		$content = new Content();		
		$form = $this->createForm(new ContentTaxonomyPopupType(), $content);			
		$pagination = $this->getDoctrine()
		        		   ->getRepository('CAFContentBundle:Content')          
		        		   ->getPagination($nb_elem);				
		if(!$contents)
			return array('contents' => null, 'form' => $form->createView(), 'content_taxonomy' => $content_taxonomy,'nb_pages' => $pagination, 'nb_elem' => $nb_elem, 'link' => '', 'taxonomy' => $taxonomy);

		return array('contents' => $contents,'nb_pages' => $pagination, 'nb_elem' => $nb_elem, 'form' => $form->createView(), 'link' => '', 'content_taxonomy' => $content_taxonomy, 'taxonomy' => $taxonomy);
	}

	/**
	 * @Route("/new", name="new_content")
	 * @Template("CAFContentBundle:Content:new.html.twig")
	 */
	public function newAction(Request $request)
	{
		$content = new Content();

		$loader = new ContentLoader();
		$loader->load($content);

		$metas = new Metas();

		$content_taxonomy_popup = $request->get('content_taxonomy_popup');
		$id_content_taxonomy = $content_taxonomy_popup['id_content_taxonomy'];

		$repo_lang  = $this->getDoctrine()->getRepository('CAFAdminBundle:Language');

		$canonicals = $loader->getCanonicals($content->getTranslations());
		
		$form_fr = $this->createForm(new ContentType(), $content, array('lang' => 'fr', 'lang_id' => $repo_lang->findBy(array('code' => 'fr')), 'content_taxonomy' => $id_content_taxonomy, 'canonical' => ''));
		$form_en = $this->createForm(new ContentType(), $content, array('lang' => 'en', 'lang_id' => $repo_lang->findBy(array('code' => 'en')), 'content_taxonomy' => $id_content_taxonomy, 'canonical' => ''));
		$form_de = $this->createForm(new ContentType(), $content, array('lang' => 'de', 'lang_id' => $repo_lang->findBy(array('code' => 'de')), 'content_taxonomy' => $id_content_taxonomy, 'canonical' => ''));

		if ($request->getMethod() == 'POST') {

			$form_fr->bindRequest($request);
			$form_en->bindRequest($request);
			$form_de->bindRequest($request);

			$content_request = $request->get('content');
			$metas = $request->get('metas');
			$content_taxonomy = $this->getDoctrine()
								 ->getRepository('CAFContentBundle:ContentTaxonomy')
								 ->find($request->get('content_taxonomy'));
			$content->setIdContentTaxonomy($content_taxonomy);
			//if ($form_fr->isValid()) {
				$titles = array();
				$langs = array(1 => 'fr', 2 => 'en', 3 => 'de');
				foreach($langs as $id=>$l) {
					$lang = $this->getDoctrine()
								 ->getRepository('CAFAdminBundle:Language')
								 ->find($id);			 
					$content->{'translation'.ucfirst($l)}->setLang($lang);
					$content->{'translation'.ucfirst($l)}->setAlias();
					$content->{'translation'.ucfirst($l)}->setUrls();
					$content->{'translation'.ucfirst($l)}->setPublished(1);
					$content->addContentTranslation($content->{'translation'.ucfirst($l)});
					$content->{'translation'.ucfirst($l)}->setContent($content);
				}

				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($content);
				$em->flush();

                $translations = $content->getTranslations();
				
                if(isset($content_request['valuesFr']))
					$valuesFr = $content_request['valuesFr'];
				else
					$valuesFr = array();

				if (isset($content_request['valuesEn'])) 
					$valuesEn = $content_request['valuesEn'];
				else
					$valuesEn = array();

				if (isset($content_request['valuesDe']))
					$valuesDe = $content_request['valuesDe'];
				else
					$valuesDe = array();

				$files = $request->files->get('content');
				if (isset($files['valuesFr'])) 
					$filesFr = $files['valuesFr'];
				else
					$filesFr = array();

				if (isset($files['valuesEn']))
					$filesEn = $files['valuesEn'];
				else
					$filesEn = array();

				if (isset($files['valuesDe']))
					$filesDe = $files['valuesDe'];
				else
					$filesDe = array();

				$metasvaluesFr = $content_request['metasValuesFr'];
				$metasvaluesEn = $content_request['metasValuesEn'];
				$metasvaluesDe = $content_request['metasValuesDe'];
				$repository = $this->getDoctrine()->getRepository('CAFContentBundle:Fields');
				$meta_repo = $this->getDoctrine()->getRepository('CAFContentBundle:Metas');
				$path_upload= $this->container->getParameter("path_upload");

				foreach($translations as $t) {
					$t->setContentUrls();
					switch ($t->getLang()->getCode()) {
						case 'fr':
							$t->setFieldsValue(new \Doctrine\Common\Collections\ArrayCollection());
							$t = $loader->loadContentTranslation($path_upload,$t, $valuesFr, $filesFr, $metasvaluesFr, 'fr', 'fr', $repository,$meta_repo, $content_taxonomy);
							break;
						case 'en':
							$t->setFieldsValue(new \Doctrine\Common\Collections\ArrayCollection());
							$t = $loader->loadContentTranslation($path_upload,$t, $valuesEn, $filesEn, $metasvaluesEn, 'en', 'en', $repository, $meta_repo, $content_taxonomy);
							break;
						case 'de':
							$t->setFieldsValue(new \Doctrine\Common\Collections\ArrayCollection());
							$t = $loader->loadContentTranslation($path_upload,$t, $valuesDe, $filesDe, $metasvaluesDe, 'de', 'de', $repository, $meta_repo, $content_taxonomy);
							break;

					}		
				}
				
				$em->persist($content);
				$em->flush();

				$this->get('session')->setFlash('success', 'Nouveau contenu sauvegardé');
				return $this->redirect($this->generateUrl('content'));
			//}
		}
		return array('form_fr' => $form_fr->createView(), 'form_en' => $form_en->createView(), 'form_de' => $form_de->createView(), 'taxonomy' => $id_content_taxonomy);
	}

	/**
	 * @Route("/edit/{id}", name="edit_content")
	 * @Template("CAFContentBundle:Content:new.html.twig")
	 */
	public function editAction(Request $request,$id)
	{
		$content = $this->getDoctrine()->getRepository('CAFContentBundle:Content')->find($id);

		$loader = new ContentLoader();

		$fieldsvalue_repo = $this->getDoctrine()->getRepository('CAFContentBundle:FieldsValue');
		$metasvalue_repo = $this->getDoctrine()->getRepository('CAFContentBundle:MetasValue');

		$loader->loadContent($content,$fieldsvalue_repo,$metasvalue_repo);

		$metas = new Metas();
		$id_content_taxonomy = intval($content->getIdContentTaxonomy()->getId());
		$repo_lang  = $this->getDoctrine()->getRepository('CAFAdminBundle:Language');

		$canonicals = $loader->getCanonicals($content->getTranslations());
		
		$form_fr = $this->createForm(new ContentType(), $content, array('lang' => 'fr', 'lang_id' => $repo_lang->findBy(array('code' => 'fr')), 'content_taxonomy' => $id_content_taxonomy, 'canonical' => $canonicals['fr']));
		$form_en = $this->createForm(new ContentType(), $content, array('lang' => 'en', 'lang_id' => $repo_lang->findBy(array('code' => 'en')), 'content_taxonomy' => $id_content_taxonomy, 'canonical' => $canonicals['en']));
		$form_de = $this->createForm(new ContentType(), $content, array('lang' => 'de', 'lang_id' => $repo_lang->findBy(array('code' => 'de')), 'content_taxonomy' => $id_content_taxonomy, 'canonical' => $canonicals['de']));


		if ($request->getMethod() == 'POST') {

			/*$form_fr->bindRequest($request);
			$form_en->bindRequest($request);
			$form_de->bindRequest($request);*/

			$content_request = $request->get('content');

			//if ($form_fr->isValid()) {
				$titles = array();
				$langs = array(1 => 'fr', 2 => 'en', 3 => 'de');
				foreach($langs as $id=>$l) {
					$lang = $this->getDoctrine()
								 ->getRepository('CAFAdminBundle:Language')
								 ->find($id);			 
					$content->{'translation'.ucfirst($l)}->setLang($lang);
					$content->{'translation'.ucfirst($l)}->setUrls();
					$content->{'translation'.ucfirst($l)}->setAlias($content_request['translation'.ucfirst($l)]['alias']);
					$content->{'translation'.ucfirst($l)}->setTitle($content_request['translation'.ucfirst($l)]['title']);
					$repo = $this->getDoctrine()->getRepository('CAFContentBundle:CategoryTranslation');
					$collection_categories = $loader->arrayToCollectionCategory($content_request['translation'.ucfirst($l)]['categories'],$repo);
					$content->{'translation'.ucfirst($l)}->setCategories($collection_categories);
				}

                
				$em = $this->getDoctrine()->getEntityManager();
				
				$translations = $content->getTranslations();
				$content_taxonomy = $content->getIdContentTaxonomy(); 
				
				$files = $request->files->get('content');

				if(isset($content_request['valuesFr']))
					$valuesFr = $content_request['valuesFr'];
				else
					$valuesFr = array();

				if (isset($content_request['valuesEn'])) 
					$valuesEn = $content_request['valuesEn'];
				else
					$valuesEn = array();

				if (isset($content_request['valuesDe']))
					$valuesDe = $content_request['valuesDe'];
				else
					$valuesDe = array();

				$files = $request->files->get('content');
				if (isset($files['valuesFr'])) 
					$filesFr = $files['valuesFr'];
				else
					$filesFr = array();

				if (isset($files['valuesEn']))
					$filesEn = $files['valuesEn'];
				else
					$filesEn = array();

				if (isset($files['valuesDe']))
					$filesDe = $files['valuesDe'];
				else
					$filesDe = array();
				
				$configUpload = new ConfigurationUpload();
	
				$metasvaluesFr = $content_request['metasValuesFr'];
				$metasvaluesEn = $content_request['metasValuesEn'];
				$metasvaluesDe = $content_request['metasValuesDe'];

				$repository = $this->getDoctrine()->getRepository('CAFContentBundle:Fields');
				$meta_repo = $this->getDoctrine()->getRepository('CAFContentBundle:Metas');
				$path_upload= $this->container->getParameter("path_upload");
				foreach($translations as $t) {
					
					
					switch ($t->getLang()->getCode()) {
						case 'fr':
							$t = $loader->updateContentTranslation($path_upload, $t, $valuesFr, $filesFr, $metasvaluesFr, 'fr', 'fr', $repository, $meta_repo, $content_taxonomy);
							break;
						case 'en':
							$t = $loader->updateContentTranslation($path_upload,$t, $valuesEn, $filesEn, $metasvaluesEn, 'en', 'en', $repository, $meta_repo, $content_taxonomy);
							break;
						case 'de':
							$t = $loader->updateContentTranslation($path_upload,$t, $valuesDe, $filesDe, $metasvaluesDe, 'de', 'de', $repository, $meta_repo, $content_taxonomy);
							break;
					}
					$content_urls = $t->getContentUrls();
					if(is_object($content_urls)) {
						$t->setContentUrls($content_urls);
					} else {
						$t->setContentUrls('generate');

					}
				}

				$em->persist($content);
				$em->flush();

				$this->get('session')->setFlash('success', 'Nouveau contenu sauvegardé');
				return $this->redirect($this->generateUrl('content'));
		}
		return array('form_fr' => $form_fr->createView(), 'form_en' => $form_en->createView(), 'form_de' => $form_de->createView(), 'taxonomy' => $id_content_taxonomy, 'id' => $id);
	}

	/**
	 * @Route("/delete/{id}", name="delete_content")
	 * @Template()
	 */
	public function deleteAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$content = $this->getDoctrine()
						->getRepository('CAFContentBundle:Content')
						->find($id);				

		if (!$content) {
			$this->get('session')->setFlash('error', "Ce contenu n\'existe pas!");
			return $this->redirect($this->generateUrl('content_taxonomy'));
		}

		$translations = $content->getTranslations();
		foreach ($translations as $t) {
			$fieldsvalue = $t->getFieldsvalue();
			$metas = $t->getMetasValue();
			foreach ($fieldsvalue as $value) {
				$em->remove($value);
			}
			foreach ($metas as $meta) {
				$em->remove($meta);
			}
			
			$em->remove($t);
		}


		$em->remove($content);
		$em->flush();
		$this->get('session')->setFlash('success', 'Contenu supprimé');
		
		return $this->redirect($this->generateUrl('content'));
	}



	/**
	 * @Route("/published/", name="publish_translation")
	 */
	public function pulishTranslationAction(Request $request) {
		
		$id    = $request->get('id');
		$state = $request->get('state');

		$em = $this->getDoctrine()->getEntityManager();
		$translation = $this->getDoctrine()
					  ->getRepository('CAFContentBundle:ContentTranslation')
					  ->find($id);
		$translation->setPublished($state);
		$em->persist($translation);
		$em->flush();
		return new Response('');
	}

	/**
	 * @Route("/action", name="contentgroup")
	 * @Template()
	 */
	public function contentGroupAction(Request $request) {
		$listid=$request->request->get('listid');
		$selectaction = $request->request->get('selectaction');
		$filter = $request->request->get('filter');
		$nb_elem = $request->request->get('nb_elem');
		if($selectaction == 'pagination' && $nb_elem > 0) {
			return $this->redirect($this->generateUrl('content', array('nb_elem' => $nb_elem, 'page' => 1 )));
		}

		$erreur=0;
		if(count($listid) && $request->getMethod() == 'POST'){
			$em = $this->getDoctrine()->getEntityManager();
			foreach ($listid as $id) {
				$translation = $this->getDoctrine()
					 ->getRepository('CAFContentBundle:ContentTranslation')
					 ->find($id);
				if (is_object($translation)) {
					switch($selectaction) {
						case 'delete':						
							$em->remove($translation);
							$em->flush();
							break;
						case "unpublish":
							$translation->setPublished(0);
							$em->persist($translation);
							$em->flush();
							break;
						case "publish":
							$translation->setPublished(1);
							$em->persist($translation);
							$em->flush();
							break;
					}				
					
				}
			}
			$this->get('session')->setFlash('success', 'L\'action choisie a bien été réalisée');		
		}
		return $this->redirect($this->generateUrl('content'));
		
	}

	/**
	 * @Route("/filter", name="filter_content")
	 */
	public function filterContentAction(Request $request)
	{
		$filter = $request->request->get('filter_type');
		return $this->redirect($this->generateUrl('content', array('nb_elem' => 20, 'page' => 1, 'taxonomy' => $filter)));
	}
}