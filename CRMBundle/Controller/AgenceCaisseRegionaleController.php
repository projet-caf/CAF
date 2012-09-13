<?php

namespace CAF\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CAF\CRMBundle\Entity\AgenceCaisseRegionale;
use CAF\CRMBundle\Form\AgenceCaisseRegionaleType;

/**
 * @Route("/agenceCaisseRegionale")
 */
class AgenceCaisseRegionaleController extends Controller
{
	
	/**
	 * @Route("/", name="agenceCaisseRegionale")
	 * @Template()
	 **/
	public function indexAction()
	{
		$agenceCaisseRegionales = $this->getDoctrine()
		        ->getRepository('CAFCRMBundle:AgenceCaisseRegionale')
		        ->findAll();

		if (!$agenceCaisseRegionales) {
        	return array('agenceCaisseRegionales' => null);
    	}
		return array('agenceCaisseRegionales' => $agenceCaisseRegionales);
	}
	
	/**
	 * @Route("/new", name="new_agenceCaisseRegionale")
	 * @Template()
	 **/
	public function newAgenceCaisseRegionaleAction(Request $request)
	{
		$agenceCaisseRegionale = new AgenceCaisseRegionale();
		$form = $this->createForm(new AgenceCaisseRegionaleType(), $agenceCaisseRegionale);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {

				$em = $this->getDoctrine()->getEntityManager();							
			    $em->persist($agenceCaisseRegionale);
			    $em->flush();
			    $this->get('session')->setFlash('success', 'La nouvelle agence a bien été sauvegardée!');
				return $this->redirect($this->generateUrl('agenceCaisseRegionale'));

			}
		}
		/*$agences = $this->getDoctrine()
		        ->getRepository('CAFCRMBundle:Agence')
		        ->findAll();*/
		//return array('form' => $form->createView(),'agences' => $agences);
		return array('form' => $form->createView());
		
	}

	/**
	 * @Route("/edit/{id}", name="edit_agenceCaisseRegionale")
	 * @Template("CAFCRMBundle:AgenceCaisseRegionale:newAgenceCaisseRegionale.html.twig")
	 */
	public function editAgenceCaisseRegionaleAction(Request $request,$id) {
		$agenceCaisseRegionale = $this->getDoctrine()
					 ->getRepository('CAFCRMBundle:AgenceCaisseRegionale')
					 ->find($id);

		$form = $this->createForm(new AgenceCaisseRegionaleType(),$agenceCaisseRegionale);
		
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($agenceCaisseRegionale);
				$em->flush();
				$this->get('session')->setFlash('success', 'L\'agence a bien été éditée');
				return $this->redirect($this->generateUrl('agenceCaisseRegionale'));
			}
		}
		return array('form' => $form->createView(), 'id' => $id);
	}

	
	/**
	 * @Route("/published/", name="publish_agenceCaisseRegionale")
	 */
	public function pulishFieldAction(Request $request) {
		
		$id    = $request->get('id');
		$state = $request->get('state');

		$em = $this->getDoctrine()->getEntityManager();
		$field = $this->getDoctrine()
					  ->getRepository('CAFCRMBundle:AgenceCaisseRegionale')
					  ->find($id);
		$field->setPublished($state);
		$em->persist($field);
		$em->flush();
		return new Response('');
	}

	 /**
	 * @Route("/action", name="action_agenceCaisseRegionale")
	 * @Template()
	 */
	public function actionAgenceCaisseRegionaleAction(Request $request) {
		$listid=$request->request->get('listid');
		$selectaction=$request->request->get('selectaction');
		$erreur=0;
		if(count($listid) && $request->getMethod() == 'POST'){
			$em = $this->getDoctrine()->getEntityManager();
			foreach ($listid as $id) {
				$agenceCaisseRegionale = $this->getDoctrine()
					 ->getRepository('CAFCRMBundle:AgenceCaisseRegionale')
					 ->find($id);
				if (is_object($agenceCaisseRegionale)) {					
					if($selectaction=="unpublish"){
						$agenceCaisseRegionale->setPublished(0);
						$em->persist($agenceCaisseRegionale);
						$em->flush();
					}
					elseif($selectaction=="publish"){
						$agenceCaisseRegionale->setPublished(1);
						$em->persist($agenceCaisseRegionale);
						$em->flush();
					}
				}
			}
			$this->get('session')->setFlash('success', 'L\'action choisie a bien été réalisée');		
		}
		return $this->redirect($this->generateUrl('agenceCaisseRegionale'));
		
	}

}