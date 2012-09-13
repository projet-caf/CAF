<?php

namespace CAF\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CAF\CRMBundle\Entity\StatutDemande;
use CAF\CRMBundle\Form\StatutDemandeType;

/**
 * @Route("/statutDemande")
 */
class StatutDemandeController extends Controller
{
	
	/**
	 * @Route("/", name="statutDemande")
	 * @Template()
	 **/
	public function indexAction()
	{
		$statuts = $this->getDoctrine()
		        ->getRepository('CAFCRMBundle:StatutDemande')
		        ->findAll();

		if (!$statuts) {
        	return array('statuts' => null);
    	}
		return array('statuts' => $statuts);
	}
	
	/**
	 * @Route("/new", name="new_statutDemande")
	 * @Template()
	 **/
	public function newStatutDemandeAction(Request $request)
	{
		$statut = new StatutDemande();
		$form = $this->createForm(new StatutDemandeType(), $statut);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {

				$em = $this->getDoctrine()->getEntityManager();							
			    $em->persist($statut);
			    $em->flush();
			    $this->get('session')->setFlash('success', 'Le nouveau statut de demande a bien été sauvegardé!');
				return $this->redirect($this->generateUrl('statutDemande'));

			}
		}
		return array('form' => $form->createView());
		
	}

	/**
	 * @Route("/edit/{id}", name="edit_statutDemande")
	 * @Template("CAFCRMBundle:StatutDemande:newStatutDemande.html.twig")
	 */
	public function editStatutDemandeAction(Request $request,$id) {
		$statut = $this->getDoctrine()
					 ->getRepository('CAFCRMBundle:StatutDemande')
					 ->find($id);

		$form = $this->createForm(new StatutDemandeType(),$statut);
		
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($statut);
				$em->flush();
				$this->get('session')->setFlash('success', 'Le statut a bien été édité');
				return $this->redirect($this->generateUrl('statutDemande'));
			}
		}
		return array('form' => $form->createView(), 'id' => $id);
	}

	
	/**
	 * @Route("/published/", name="publish_statutDemande")
	 */
	public function pulishFieldAction(Request $request) {
		
		$id    = $request->get('id');
		$state = $request->get('state');

		$em = $this->getDoctrine()->getEntityManager();
		$field = $this->getDoctrine()
					  ->getRepository('CAFCRMBundle:StatutDemande')
					  ->find($id);
		$field->setPublished($state);
		$em->persist($field);
		$em->flush();
		return new Response('');
	}

	 /**
	 * @Route("/action", name="action_statutDemande")
	 * @Template()
	 */
	public function actionStatutDemandeAction(Request $request) {
		$listid=$request->request->get('listid');
		$selectaction=$request->request->get('selectaction');
		$erreur=0;
		if(count($listid) && $request->getMethod() == 'POST'){
			$em = $this->getDoctrine()->getEntityManager();
			foreach ($listid as $id) {
				$statut = $this->getDoctrine()
					 ->getRepository('CAFCRMBundle:StatutDemande')
					 ->find($id);
				if (is_object($statut)) {					
					if($selectaction=="unpublish"){
						$statut->setPublished(0);
						$em->persist($statut);
						$em->flush();
					}
					elseif($selectaction=="publish"){
						$statut->setPublished(1);
						$em->persist($statut);
						$em->flush();
					}
				}
			}
			$this->get('session')->setFlash('success', 'L\'action choisie a bien été réalisée');		
		}
		return $this->redirect($this->generateUrl('statutDemande'));
		
	}

}