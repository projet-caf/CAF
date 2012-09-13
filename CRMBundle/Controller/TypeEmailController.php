<?php

namespace CAF\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CAF\CRMBundle\Entity\TypeEmail;
use CAF\CRMBundle\Form\TypeEmailType;

/**
 * @Route("/typeEmail")
 */
class TypeEmailController extends Controller
{
	
	/**
	 * @Route("/", name="typeEmail")
	 * @Template()
	 **/
	public function indexAction()
	{
		$typeEmails = $this->getDoctrine()
		        ->getRepository('CAFCRMBundle:TypeEmail')
		        ->findAll();

		if (!$typeEmails) {
        	return array('typeEmails' => null);
    	}
		return array('typeEmails' => $typeEmails);
	}
	
	/**
	 * @Route("/new", name="new_typeEmail")
	 * @Template()
	 **/
	public function newTypeEmailAction(Request $request)
	{
		$typeEmail = new TypeEmail();
		$form = $this->createForm(new TypeEmailType(), $typeEmail);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {

				$em = $this->getDoctrine()->getEntityManager();							
			    $em->persist($typeEmail);
			    $em->flush();
			    $this->get('session')->setFlash('success', 'Le nouveau type d\'email a bien été sauvegardé!');
				return $this->redirect($this->generateUrl('typeEmail'));

			}
		}
		return array('form' => $form->createView());
		
	}

	/**
	 * @Route("/edit/{id}", name="edit_typeEmail")
	 * @Template("CAFCRMBundle:TypeEmail:newTypeEmail.html.twig")
	 */
	public function editTypeEmailAction(Request $request,$id) {
		$typeEmail = $this->getDoctrine()
					 ->getRepository('CAFCRMBundle:TypeEmail')
					 ->find($id);

		$form = $this->createForm(new TypeEmailType(),$typeEmail);
		
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($typeEmail);
				$em->flush();
				$this->get('session')->setFlash('success', 'Le type d\'email a bien été éditée');
				return $this->redirect($this->generateUrl('typeEmail'));
			}
		}
		return array('form' => $form->createView(), 'id' => $id);
	}

	
	/**
	 * @Route("/published/", name="publish_typeEmail")
	 */
	public function pulishFieldAction(Request $request) {
		
		$id    = $request->get('id');
		$state = $request->get('state');

		$em = $this->getDoctrine()->getEntityManager();
		$field = $this->getDoctrine()
					  ->getRepository('CAFCRMBundle:TypeEmail')
					  ->find($id);
		$field->setPublished($state);
		$em->persist($field);
		$em->flush();
		return new Response('');
	}

	 /**
	 * @Route("/action", name="action_typeEmail")
	 * @Template()
	 */
	public function actionTypeEmailAction(Request $request) {
		$listid=$request->request->get('listid');
		$selectaction=$request->request->get('selectaction');
		$erreur=0;
		if(count($listid) && $request->getMethod() == 'POST'){
			$em = $this->getDoctrine()->getEntityManager();
			foreach ($listid as $id) {
				$typeEmail = $this->getDoctrine()
					 ->getRepository('CAFCRMBundle:TypeEmail')
					 ->find($id);
				if (is_object($typeEmail)) {					
					if($selectaction=="unpublish"){
						$typeEmail->setPublished(0);
						$em->persist($typeEmail);
						$em->flush();
					}
					elseif($selectaction=="publish"){
						$typeEmail->setPublished(1);
						$em->persist($typeEmail);
						$em->flush();
					}
				}
			}
			$this->get('session')->setFlash('success', 'L\'action choisie a bien été réalisée');		
		}
		return $this->redirect($this->generateUrl('typeEmail'));
		
	}

}