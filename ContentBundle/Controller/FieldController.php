<?php

namespace CAF\ContentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CAF\ContentBundle\Entity\Fields;
use CAF\ContentBundle\Form\FieldType;

/**
 * @Route("/field")
 */
class FieldController extends Controller
{

	/**
	 * @Route("/", name="fields")
	 * @Template()
	 */
	public function indexAction()
	{
		$fields = $this->getDoctrine()
						->getRepository('CAFContentBundle:Fields')
						->findAllAndType();			
		if(!$fields)
			return array('fields' => null);
		return array('fields' => $fields);
	}

	/**
	 * @Route("/new", name="new_field")
	 * @Template("CAFContentBundle:Field:new.html.twig")
	 */
	public function newAction(Request $request)
	{
		$field = new Fields();
		$form = $this->createForm(new FieldType(), $field);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
				if($field->getOrdre() == null) {
					$lastOrdre = $this->getDoctrine()
									   ->getRepository('CAFContentBundle:Fields')
									   ->lastOrdre();
					$lastOrdre = $lastOrdre->getOrdre()+1;
					$field->setOrdre($lastOrdre);
									   
				} else {
					$ordre = $field->getOrdre()->getOrdre();
					$ordre++;
					$field->setOrdre($ordre);
					$this->getDoctrine()
						 ->getRepository('CAFContentBundle:Fields')
						 ->decaler();
				}
				$em->persist($field);
				$em->flush();
				$this->get('session')->setFlash('success', 'Le champ a bien été sauvegardé');
				return $this->redirect($this->generateUrl('fields'));
			}
		}
		return array('form' => $form->createView());
	}

	/**
	 * @Route("/edit/{id}", name="edit_field")
	 * @Template("CAFContentBundle:Field:new.html.twig")
	 */
	public function editAction(Request $request,$id)
	{
		$field = $this->getDoctrine()
						->getRepository('CAFContentBundle:Fields')
						->find($id);
		$form = $this->createForm(new FieldType(), $field);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
				if($field->getOrdre() == null) {
					$lastOrdre = $this->getDoctrine()
									   ->getRepository('CAFContentBundle:Fields')
									   ->lastOrdre();
					$lastOrdre = $lastOrdre->getOrdre()+1;
					$field->setOrdre($lastOrdre);
									   
				} else {
					$ordre = $field->getOrdre()->getOrdre();
					$ordre++;
					$field->setOrdre($ordre);
					$this->getDoctrine()
						 ->getRepository('CAFContentBundle:Fields')
						 ->decaler($ordre);
				}
				$em->persist($field);
				$em->flush();
				$this->get('session')->setFlash('success', 'Le champ a bien été sauvegardé');
				return $this->redirect($this->generateUrl('fields'));
			}
		}
		return array('form' => $form->createView(), 'id' => $id);
	}

	/**
	 * @Route("/delete/{id}", name="delete_field")
	 * @Template()
	 */
	public function deleteAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$field = $this->getDoctrine()
						->getRepository('CAFContentBundle:Fields')
						->find($id);
		if (!$field) {
			$this->get('session')->setFlash('error', "Ce champ n'existe pas");
			return $this->redirect($this->generateUrl('fields'));
		}

		$em->remove($field);
		$em->flush();
		
		$this->get('session')->setFlash('success', 'Ce champ a bien été supprimé');
		return $this->redirect($this->generateUrl('fields'));
	}

	/**
	 * @Route("/published/", name="publish_field")
	 */
	public function pulishFieldAction(Request $request) {
		
		$id    = $request->get('id');
		$state = $request->get('state');

		$em = $this->getDoctrine()->getEntityManager();
		$field = $this->getDoctrine()
					  ->getRepository('CAFContentBundle:Fields')
					  ->find($id);
		$field->setPublished($state);
		$em->persist($field);
		$em->flush();
		return new Response('');
	}

	/**
	 * @Route("/mandatory/", name="mandatory_field")
	 */
	public function mandatoryFieldAction(Request $request) {
		
		$id    = $request->get('id');
		$state = $request->get('state');

		$em = $this->getDoctrine()->getEntityManager();
		$field = $this->getDoctrine()
					  ->getRepository('CAFContentBundle:Fields')
					  ->find($id);
		$field->setRequired($state);
		$em->persist($field);
		$em->flush();
		return new Response('');
	}
}