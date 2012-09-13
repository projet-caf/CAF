<?php

namespace CAF\FormBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CAF\FormBundle\Entity\FormFieldTaxonomy;
use CAF\FormBundle\Form\FormFieldTaxonomyType;

/**
 * @Route("formfield-taxonomy")
 */
class FormFieldTaxonomyController extends Controller
{

	/**
	 * @Route("/", name="formfield_taxonomy")
	 * @Template()
	 */
	public function indexAction()
	{
		$taxonomies = $this->getDoctrine()
						->getRepository('CAFFormBundle:FormFieldTaxonomy')
						->findAll();
		if(!$taxonomies)
			return array('taxonomies' => null);

		return array('taxonomies' => $taxonomies);
	}

	/**
	 * @Route("/new", name="new_formfield_taxonomy")
	 * @Template("CAFFormBundle:FormFieldTaxonomy:new.html.twig")
	 */
	public function newAction(Request $request)
	{
		$taxonomy = new FormFieldTaxonomy();
		$form = $this->createForm(new FormFieldTaxonomyType(), $taxonomy);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($taxonomy);
				$em->flush();
				$this->get('session')->setFlash('success', 'Le nouveau champ a bien été enregistré');
				return $this->redirect($this->generateUrl('formfield_taxonomy'));
			}
		}
		return array('form' => $form->createView());
	}

	/**
	 * @Route("/edit/{id}", name="edit_formfield_taxonomy")
	 * @Template("CAFFormBundle:FormFieldTaxonomy:new.html.twig")
	 */
	public function editAction(Request $request,$id)
	{
		$taxonomy = $this->getDoctrine()
						->getRepository('CAFFormBundle:FormFieldTaxonomy')
						->find($id);
		$form = $this->createForm(new FormFieldTaxonomyType(), $taxonomy);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($taxonomy);
				$em->flush();
				$this->get('session')->setFlash('success', 'Le champ a bien été enregistré');
				return $this->redirect($this->generateUrl('formfield_taxonomy'));
			}
		}
		return array('form' => $form->createView(), 'id' => $id);
	}

	/**
	 * @Route("/delete/{id}", name="delete_formfield_taxonomy")
	 * @Template()
	 */
	public function deleteAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$taxonomy = $this->getDoctrine()
						->getRepository('CAFFormBundle:FormFieldTaxonomy')
						->find($id);
		if (!$taxonomy) {
			$this->get('session')->setFlash('error', "Ce type de champ n'existe pas");
			return $this->redirect($this->generateUrl('formfield_taxonomy'));
		}

		if(count($taxonomy->getFormFields())) {
			 $this->get('session')->setFlash('error', 'Des champs sont rattaché à ce type de champ. Supprimez-les avant de supprimer ce type de champ');
		} else {
			$em->remove($taxonomy);
			$em->flush();
			$this->get('session')->setFlash('success', 'Ce type de champ a été supprimé');
		}
		return $this->redirect($this->generateUrl('formfield_taxonomy'));
	}
}