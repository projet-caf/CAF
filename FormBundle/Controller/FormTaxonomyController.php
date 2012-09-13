<?php

namespace CAF\FormBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CAF\FormBundle\Entity\FormTaxonomy;
use CAF\FormBundle\Form\FormTaxonomyType;


use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @Route("/form-taxonomy")
 */
class FormTaxonomyController extends Controller
{

	/**
	 * @Route("/taxonomy/{page}/{nb_elem}", name="form_taxonomy", defaults={"page"=1, "nb_elem"=10})
	 * @Template()
	 */
	public function indexAction($page,$nb_elem)
	{
		$taxonomies = $this->getDoctrine()
						->getRepository('CAFFormBundle:FormTaxonomy')
						->findAllOrder($page, $nb_elem);

		$pagination = $this->getDoctrine()
		        		   ->getRepository('CAFFormBundle:FormTaxonomy')          
		        		   ->getPagination($nb_elem);

		if(!$taxonomies)
			return array('taxonomies' => null);

		return array('taxonomies' => $taxonomies, 'nb_pages' => $pagination, 'nb_elem' => $nb_elem, 'link' => '');
	}

	/**
	 * @Route("/new", name="new_form_taxonomy")
	 * @Template("CAFFormBundle:FormTaxonomy:new.html.twig")
	 */
	public function newAction(Request $request)
	{
		$taxonomy = new FormTaxonomy();

		$form = $this->createForm(new FormTaxonomyType(), $taxonomy);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
				$taxonomy->setFormFields($taxonomy->getFormFields());
				$em->persist($taxonomy);
				$em->flush();
				$this->get('session')->setFlash('success', 'Nouveau type de formulaire sauvegardé');
				return $this->redirect($this->generateUrl('form_taxonomy'));
			}
		}
		return array('form' => $form->createView());
	}

	/**
	 * @Route("/edit/{id}", name="edit_form_taxonomy")
	 * @Template("CAFFormBundle:FormTaxonomy:new.html.twig")
	 */
	public function editAction(Request $request,$id)
	{
		$taxonomy = $this->getDoctrine()
						->getRepository('CAFFormBundle:FormTaxonomy')
						->find($id);
							
		
		$form = $this->createForm(new FormTaxonomyType(), $taxonomy);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
				$taxonomy->setFormFields($taxonomy->getFormFields());
				$em->persist($taxonomy);
				$em->flush();
				$this->get('session')->setFlash('success', 'Le type de formulaire a été édité');
				return $this->redirect($this->generateUrl('form_taxonomy'));
			}
		}
		return array('form' => $form->createView(), 'id' => $id);
	}

	/**
	 * @Route("/delete/{id}", name="delete_form_taxonomy")
	 * @Template()
	 */
	public function deleteAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$taxonomy = $this->getDoctrine()
						->getRepository('CAFFormBundle:FormTaxonomy')
						->find($id);
		if (!$taxonomy) {
			$this->get('session')->setFlash('error', "Ce type de formulaire n'existe pas");
			return $this->redirect($this->generateUrl('form_taxonomy'));
		}

		if(count($taxonomy->getContents())) {
			 $this->get('session')->setFlash('error', 'Ce type de formulaire a des formulaires attachés. Veuillez les supprimer avant de supprimer ce type de formulaire');
		} else {
			$em->remove($taxonomy);
			$em->flush();
			$this->get('session')->setFlash('success', 'Ce type de contenu a été supprimé');
		}
		return $this->redirect($this->generateUrl('form_taxonomy'));
	}

	/**
	 * @Route("/published/", name="publish_taxonomy")
	 */
	public function pulishTranslationAction(Request $request) {
		
		$id    = $request->get('id');
		$state = $request->get('state');

		$em = $this->getDoctrine()->getEntityManager();
		$form_taxonomy = $this->getDoctrine()
					  ->getRepository('CAFFormBundle:FormTaxonomy')
					  ->find($id);
		$form_taxonomy->setPublished($state);
		$em->persist($form_taxonomy);
		$em->flush();
		return new Response('');
	}


	/**
	 * @Route("/action", name="form_taxonomy_action")
	 * @Template()
	 */
	public function formTaxonomyGroupAction(Request $request)
	{
		$listid=$request->request->get('listid');
		$selectaction=$request->request->get('selectaction');
		$nb_elem = $request->request->get('nb_elem');

		if($selectaction == 'pagination' && $nb_elem > 0) {
			return $this->redirect($this->generateUrl('form_taxonomy', array('nb_elem' => $nb_elem)));
		}

		$erreur=0;
		if(count($listid) && $request->getMethod() == 'POST'){
			$em = $this->getDoctrine()->getEntityManager();
			foreach ($listid as $id) {
				$form_taxonomy = $this->getDoctrine()
					 ->getRepository('CAFFormBundle:FormTaxonomy')
					 ->find($id);
				if (is_object($form_taxonomy)) {
					switch($selectaction) {
						case 'delete':						
							$em->remove($form_taxonomy);
							$em->flush();
							break;
						case "unpublish":
							$form_taxonomy->setPublished(0);
							$em->persist($form_taxonomy);
							$em->flush();
							break;
						case "publish":
							$form_taxonomy->setPublished(1);
							$em->persist($form_taxonomy);
							$em->flush();
							break;
					}				
					
				}
			}
			$this->get('session')->setFlash('success', 'L\'action choisie a bien été réalisée');		
		}
		return $this->redirect($this->generateUrl('form_taxonomy'));
	}

}