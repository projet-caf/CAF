<?php

namespace CAF\FormBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CAF\FormBundle\Entity\FormFields;
use CAF\FormBundle\Form\FormFieldsType;

/**
 * @Route("/formfields")
 */
class FormFieldsController extends Controller
{

	/**
	 * @Route("/", name="formfields" )
	 * @Template()
	 */
	public function indexAction()
	{
		$fields = $this->getDoctrine()
						->getRepository('CAFFormBundle:FormFields')
						->findAllAndType();			
		if(!$fields)
			return array('fields' => null);
		return array('fields' => $fields);
	}

	/**
	 * @Route("/new", name="new_formfield")
	 * @Template("CAFFormBundle:FormFields:new.html.twig")
	 */
	public function newAction(Request $request)
	{
		$field = new FormFields();
		$form = $this->createForm(new FormFieldsType(), $field);

		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
				if($field->getOrdre() == null) {
					$lastOrdre = $this->getDoctrine()
									   ->getRepository('CAFFormBundle:FormFields')
									   ->lastOrdre();
					if($lastOrdre!=null){
						$lastOrdre = $lastOrdre->getOrdre()+1;
					}
					else{
						$lastOrdre = 1;
					}					
					$field->setOrdre($lastOrdre);
									   
				} else {
					$ordre = $field->getOrdre()->getOrdre();
					$ordre++;
					$field->setOrdre($ordre);
					$this->getDoctrine()
						 ->getRepository('CAFFormBundle:FormFields')
						 ->decaler($ordre);
				}
				$em->persist($field);
				$em->flush();
				$this->get('session')->setFlash('success', 'Le champ a bien été sauvegardé');
				return $this->redirect($this->generateUrl('formfields'));
			}
		}
		return array('form' => $form->createView());
	}

	/**
	 * @Route("/edit/{id}", name="edit_formfield")
	 * @Template("CAFFormBundle:FormFields:new.html.twig")
	 */
	public function editAction(Request $request,$id)
	{
		$field = $this->getDoctrine()
						->getRepository('CAFFormBundle:FormFields')
						->find($id);
		$form = $this->createForm(new FormFieldsType(), $field);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
				if($field->getOrdre() == null) {
					$lastOrdre = $this->getDoctrine()
									   ->getRepository('CAFFormBundle:FormFields')
									   ->lastOrdre();
					$lastOrdre = $lastOrdre->getOrdre()+1;
					$field->setOrdre($lastOrdre);
									   
				} else {
					$ordre = $field->getOrdre()->getOrdre();
					$ordre++;
					$field->setOrdre($ordre);
					$this->getDoctrine()
						 ->getRepository('CAFFormBundle:FormFields')
						 ->decaler($ordre);
				}
				$em->persist($field);
				$em->flush();
				$this->get('session')->setFlash('success', 'Le champ a bien été sauvegardé');
				return $this->redirect($this->generateUrl('formfields'));
			}
		}
		return array('form' => $form->createView(), 'id' => $id);
	}

	/**
	 * @Route("/delete/{id}", name="delete_formfield")
	 * @Template()
	 */
	public function deleteAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$field = $this->getDoctrine()
						->getRepository('CAFFormBundle:FormFields')
						->find($id);
		if (!$field) {
			$this->get('session')->setFlash('error', "Ce champ n'existe pas");
			return $this->redirect($this->generateUrl('formfields'));
		}

		$em->remove($field);
		$em->flush();
		
		$this->get('session')->setFlash('success', 'Ce champ a bien été supprimé');
		return $this->redirect($this->generateUrl('formfields'));
	}

	/**
	 * @Route("/published/", name="publish_formfield")
	 */
	public function pulishFieldAction(Request $request) {
		
		$id    = $request->get('id');
		$state = $request->get('state');

		$em = $this->getDoctrine()->getEntityManager();
		$field = $this->getDoctrine()
					  ->getRepository('CAFFormBundle:FormFields')
					  ->find($id);
		$field->setPublished($state);
		$em->persist($field);
		$em->flush();
		return new Response('');
	}

	/**
	 * @Route("/mandatory/", name="mandatory_formfield")
	 */
	public function mandatoryFieldAction(Request $request) {
		
		$id    = $request->get('id');
		$state = $request->get('state');

		$em = $this->getDoctrine()->getEntityManager();
		$field = $this->getDoctrine()
					  ->getRepository('CAFFormBundle:FormFields')
					  ->find($id);
		$field->setRequired($state);
		$em->persist($field);
		$em->flush();
		return new Response('');
	}
}