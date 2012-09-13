<?php

namespace CAF\ContentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CAF\ContentBundle\Entity\ContentTaxonomy;
use CAF\ContentBundle\Form\ContentTaxonomyType;

use CAF\ContentBundle\Configuration\ConfigurationTemplate;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @Route("/content-taxonomy")
 */
class ContentTaxonomyController extends Controller
{

	/**
	 * @Route("/taxonomy/{page}/{nb_elem}", name="content_taxonomy", defaults={"page"=1, "nb_elem"=10})
	 * @Template()
	 */
	public function indexAction($page,$nb_elem)
	{
		$taxonomies = $this->getDoctrine()
						->getRepository('CAFContentBundle:ContentTaxonomy')
						->findAllOrder($page, $nb_elem);

		$pagination = $this->getDoctrine()
		        		   ->getRepository('CAFContentBundle:Category')          
		        		   ->getPagination($nb_elem);

		if(!$taxonomies)
			return array('taxonomies' => null);

		return array('taxonomies' => $taxonomies, 'nb_pages' => $pagination, 'nb_elem' => $nb_elem, 'link' => '');
	}

	/**
	 * @Route("/new", name="new_content_taxonomy")
	 * @Template("CAFContentBundle:ContentTaxonomy:new.html.twig")
	 */
	public function newAction(Request $request)
	{
		$taxonomy = new ContentTaxonomy();
		$directory_separator = $this->container->getParameter("directory_separator");
		$config = new ConfigurationTemplate();
		$fichiers = $config->getTemplates($directory_separator);

		$form = $this->createForm(new ContentTaxonomyType(), $taxonomy, array('fichiers' => $fichiers));
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
				$taxonomy->setFields($taxonomy->getFields());
				$em->persist($taxonomy);
				$em->flush();
				$this->get('session')->setFlash('success', 'Nouveau type de contenu sauvegardé');
				return $this->redirect($this->generateUrl('content_taxonomy'));
			}
		}
		return array('form' => $form->createView());
	}

	/**
	 * @Route("/edit/{id}", name="edit_content_taxonomy")
	 * @Template("CAFContentBundle:ContentTaxonomy:new.html.twig")
	 */
	public function editAction(Request $request,$id)
	{
		$taxonomy = $this->getDoctrine()
						->getRepository('CAFContentBundle:ContentTaxonomy')
						->find($id);
		$directory_separator = $this->container->getParameter("directory_separator");				
		$config = new ConfigurationTemplate();
		$fichiers = $config->getTemplates($directory_separator);				
		
		$form = $this->createForm(new ContentTaxonomyType(), $taxonomy, array('fichiers' => $fichiers));
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
				$taxonomy->setFields($taxonomy->getFields());
				$em->persist($taxonomy);
				$em->flush();
				$this->get('session')->setFlash('success', 'Le type de contenu a été édité');
				return $this->redirect($this->generateUrl('content_taxonomy'));
			}
		}
		return array('form' => $form->createView(), 'id' => $id);
	}

	/**
	 * @Route("/delete/{id}", name="delete_content_taxonomy")
	 * @Template()
	 */
	public function deleteAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$taxonomy = $this->getDoctrine()
						->getRepository('CAFContentBundle:ContentTaxonomy')
						->find($id);
		if (!$taxonomy) {
			$this->get('session')->setFlash('error', "Ce type de contenu n'existe pas");
			return $this->redirect($this->generateUrl('content_taxonomy'));
		}

		if(count($taxonomy->getContents())) {
			 $this->get('session')->setFlash('error', 'Ce type de contenu a des articles attachés. Veuillez les supprimer avant de supprimer ce type de contenu');
		} else {
			$em->remove($taxonomy);
			$em->flush();
			$this->get('session')->setFlash('success', 'Ce type de contenu a été supprimé');
		}
		return $this->redirect($this->generateUrl('content_taxonomy'));
	}

	/**
	 * @Route("/published/", name="publish_taxonomy")
	 */
	public function pulishTranslationAction(Request $request) {
		
		$id    = $request->get('id');
		$state = $request->get('state');

		$em = $this->getDoctrine()->getEntityManager();
		$content_taxonomy = $this->getDoctrine()
					  ->getRepository('CAFContentBundle:ContentTaxonomy')
					  ->find($id);
		$content_taxonomy->setPublished($state);
		$em->persist($content_taxonomy);
		$em->flush();
		return new Response('');
	}


	/**
	 * @Route("/action", name="content_taxonomy_action")
	 * @Template()
	 */
	public function contentTaxonomyGroupAction(Request $request)
	{
		$listid=$request->request->get('listid');
		$selectaction=$request->request->get('selectaction');
		$nb_elem = $request->request->get('nb_elem');

		if($selectaction == 'pagination' && $nb_elem > 0) {
			return $this->redirect($this->generateUrl('content_taxonomy', array('nb_elem' => $nb_elem)));
		}

		$erreur=0;
		if(count($listid) && $request->getMethod() == 'POST'){
			$em = $this->getDoctrine()->getEntityManager();
			foreach ($listid as $id) {
				$content_taxonomy = $this->getDoctrine()
					 ->getRepository('CAFContentBundle:ContentTaxonomy')
					 ->find($id);
				if (is_object($content_taxonomy)) {
					switch($selectaction) {
						case 'delete':						
							$em->remove($content_taxonomy);
							$em->flush();
							break;
						case "unpublish":
							$content_taxonomy->setPublished(0);
							$em->persist($content_taxonomy);
							$em->flush();
							break;
						case "publish":
							$content_taxonomy->setPublished(1);
							$em->persist($content_taxonomy);
							$em->flush();
							break;
					}				
					
				}
			}
			$this->get('session')->setFlash('success', 'L\'action choisie a bien été réalisée');		
		}
		return $this->redirect($this->generateUrl('content_taxonomy'));
	}

}