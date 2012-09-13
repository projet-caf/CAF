<?php

namespace CAF\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CAF\CRMBundle\Entity\CaisseRegionale;
use CAF\CRMBundle\Form\CaisseRegionaleType;

/**
 * @Route("/caisseRegionale")
 */
class CaisseRegionaleController extends Controller
{
	
	/**
	 * @Route("/", name="caisseRegionale")
	 * @Template()
	 **/
	public function indexAction()
	{
		$caisseRegionales = $this->getDoctrine()
		        ->getRepository('CAFCRMBundle:CaisseRegionale')
		        ->findAll();

		if (!$caisseRegionales) {
        	return array('caisseRegionales' => null);
    	}
		return array('caisseRegionales' => $caisseRegionales);
	}
	
	/**
	 * @Route("/new", name="new_caisseRegionale")
	 * @Template()
	 **/
	public function newCaisseRegionaleAction(Request $request)
	{
		$caisseRegionale = new CaisseRegionale();
		$form = $this->createForm(new CaisseRegionaleType(), $caisseRegionale);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {

				$em = $this->getDoctrine()->getEntityManager();							
			    $em->persist($caisseRegionale);
			    $em->flush();
			    $this->get('session')->setFlash('success', 'La nouvelle caisse régionale a bien été sauvegardée!');
				return $this->redirect($this->generateUrl('caisseRegionale'));

			}
		}
		/*$agences = $this->getDoctrine()
		        ->getRepository('CAFCRMBundle:Agence')
		        ->findAll();*/
		//return array('form' => $form->createView(),'agences' => $agences);
		return array('form' => $form->createView());
		
	}

	/**
	 * @Route("/edit/{id}", name="edit_caisseRegionale")
	 * @Template("CAFCRMBundle:CaisseRegionale:newCaisseRegionale.html.twig")
	 */
	public function editCaisseRegionaleAction(Request $request,$id) {
		$caisseRegionale = $this->getDoctrine()
					 ->getRepository('CAFCRMBundle:CaisseRegionale')
					 ->find($id);

		$form = $this->createForm(new CaisseRegionaleType(),$caisseRegionale);
		
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($caisseRegionale);
				$em->flush();
				$this->get('session')->setFlash('success', 'La caisse régionale a bien été éditée');
				return $this->redirect($this->generateUrl('caisseRegionale'));
			}
		}
		return array('form' => $form->createView(), 'id' => $id);
	}

	
	/**
	 * @Route("/published/", name="publish_caisseRegionale")
	 */
	public function pulishFieldAction(Request $request) {
		
		$id    = $request->get('id');
		$state = $request->get('state');

		$em = $this->getDoctrine()->getEntityManager();
		$field = $this->getDoctrine()
					  ->getRepository('CAFCRMBundle:CaisseRegionale')
					  ->find($id);
		$field->setPublished($state);
		$em->persist($field);
		$em->flush();
		return new Response('');
	}

	 /**
	 * @Route("/action", name="action_caisseRegionale")
	 * @Template()
	 */
	public function actionCaisseRegionaleAction(Request $request) {
		$listid=$request->request->get('listid');
		$selectaction=$request->request->get('selectaction');
		$erreur=0;
		if(count($listid) && $request->getMethod() == 'POST'){
			$em = $this->getDoctrine()->getEntityManager();
			foreach ($listid as $id) {
				$caisseRegionale = $this->getDoctrine()
					 ->getRepository('CAFCRMBundle:CaisseRegionale')
					 ->find($id);
				if (is_object($caisseRegionale)) {					
					if($selectaction=="unpublish"){
						$caisseRegionale->setPublished(0);
						$em->persist($caisseRegionale);
						$em->flush();
					}
					elseif($selectaction=="publish"){
						$caisseRegionale->setPublished(1);
						$em->persist($caisseRegionale);
						$em->flush();
					}
				}
			}
			$this->get('session')->setFlash('success', 'L\'action choisie a bien été réalisée');		
		}
		return $this->redirect($this->generateUrl('caisseRegionale'));
		
	}

}