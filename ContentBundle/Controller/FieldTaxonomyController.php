<?php

namespace CAF\ContentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CAF\ContentBundle\Entity\FieldTaxonomy;
use CAF\ContentBundle\Form\FieldTaxonomyType;

/**
 * @Route("field-taxonomy")
 */
class FieldTaxonomyController extends Controller
{

	/**
	 * @Route("/", name="field_taxonomy")
	 * @Template()
	 */
	public function indexAction()
	{
		$taxonomies = $this->getDoctrine()
						->getRepository('CAFContentBundle:FieldTaxonomy')
						->findAll();
		if(!$taxonomies)
			return array('taxonomies' => null);

		return array('taxonomies' => $taxonomies);
	}

	/**
	 * @Route("/new", name="new_field_taxonomy")
	 * @Template("CAFContentBundle:FieldTaxonomy:new.html.twig")
	 */
	public function newAction(Request $request)
	{
		$taxonomy = new FieldTaxonomy();
		$form = $this->createForm(new FieldTaxonomyType(), $taxonomy);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($taxonomy);
				$em->flush();
				$this->get('session')->setFlash('success', 'New field taxonomy saved');
				return $this->redirect($this->generateUrl('field_taxonomy'));
			}
		}
		return array('form' => $form->createView());
	}

	/**
	 * @Route("/edit/{id}", name="edit_field_taxonomy")
	 * @Template("CAFContentBundle:FieldTaxonomy:new.html.twig")
	 */
	public function editAction(Request $request,$id)
	{
		$taxonomy = $this->getDoctrine()
						->getRepository('CAFContentBundle:FieldTaxonomy')
						->find($id);
		$form = $this->createForm(new FieldTaxonomyType(), $taxonomy);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($taxonomy);
				$em->flush();
				$this->get('session')->setFlash('success', 'New field taxonomy saved');
				return $this->redirect($this->generateUrl('field_taxonomy'));
			}
		}
		return array('form' => $form->createView(), 'id' => $id);
	}

	/**
	 * @Route("/delete/{id}", name="delete_field_taxonomy")
	 * @Template()
	 */
	public function deleteAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$taxonomy = $this->getDoctrine()
						->getRepository('CAFContentBundle:FieldTaxonomy')
						->find($id);
		if (!$taxonomy) {
			$this->get('session')->setFlash('error', "Ce type de champ n'existe pas");
			return $this->redirect($this->generateUrl('field_taxonomy'));
		}

		if(count($taxonomy->getFields())) {
			 $this->get('session')->setFlash('error', 'Des champs sont rattaché à ce type de champ. Supprimez-les avant de supprimer ce type de champ');
		} else {
			$em->remove($taxonomy);
			$em->flush();
			$this->get('session')->setFlash('success', 'Ce type de champ a été supprimé');
		}
		return $this->redirect($this->generateUrl('field_taxonomy'));
	}
}