<?php

namespace CAF\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CAF\CRMBundle\Entity\Agence;
use CAF\CRMBundle\Form\AgenceType;

/**
 * @Route("/agence")
 */
class AgenceController extends Controller
{
	
	/**
	 * @Route("/", name="agence")
	 * @Template()
	 **/
	public function indexAction()
	{
		$agences = $this->getDoctrine()
		        ->getRepository('CAFCRMBundle:Agence')
		        ->findAll();

		if (!$agences) {
        	return array('agences' => null);
    	}
		return array('agences' => $agences);
	}
	
	/**
	 * @Route("/new", name="new_agence")
	 * @Template()
	 **/
	public function newAgenceAction(Request $request)
	{
		$agence = new Agence();
		$form = $this->createForm(new AgenceType(), $agence);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {

				$em = $this->getDoctrine()->getEntityManager();							
			    $em->persist($agence);
			    $em->flush();
			    $this->get('session')->setFlash('success', 'La nouvelle agence a bien été sauvegardée!');
				return $this->redirect($this->generateUrl('agence'));

			}
		}
		return array('form' => $form->createView());
		
	}

	/**
	 * @Route("/edit/{id}", name="edit_agence")
	 * @Template("CAFCRMBundle:Agence:newAgence.html.twig")
	 */
	public function editAgenceAction(Request $request,$id) {
		$agence = $this->getDoctrine()
					 ->getRepository('CAFCRMBundle:Agence')
					 ->find($id);

		$form = $this->createForm(new AgenceType(),$agence);
		
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($agence);
				$em->flush();
				$this->get('session')->setFlash('success', 'L\'agence a bien été éditée');
				return $this->redirect($this->generateUrl('agence'));
			}
		}
		return array('form' => $form->createView(), 'id' => $id);
	}

	
	/**
	 * @Route("/published/", name="publish_agence")
	 */
	public function pulishFieldAction(Request $request) {
		
		$id    = $request->get('id');
		$state = $request->get('state');

		$em = $this->getDoctrine()->getEntityManager();
		$field = $this->getDoctrine()
					  ->getRepository('CAFCRMBundle:Agence')
					  ->find($id);
		$field->setPublished($state);
		$em->persist($field);
		$em->flush();
		return new Response('');
	}

	 /**
	 * @Route("/action", name="action_agence")
	 * @Template()
	 */
	public function actionAgenceAction(Request $request) {
		$listid=$request->request->get('listid');
		$selectaction=$request->request->get('selectaction');
		$erreur=0;
		if(count($listid) && $request->getMethod() == 'POST'){
			$em = $this->getDoctrine()->getEntityManager();
			foreach ($listid as $id) {
				$agence = $this->getDoctrine()
					 ->getRepository('CAFCRMBundle:Agence')
					 ->find($id);
				if (is_object($agence)) {					
					if($selectaction=="unpublish"){
						$agence->setPublished(0);
						$em->persist($agence);
						$em->flush();
					}
					elseif($selectaction=="publish"){
						$agence->setPublished(1);
						$em->persist($agence);
						$em->flush();
					}
				}
			}
			$this->get('session')->setFlash('success', 'L\'action choisie a bien été réalisée');		
		}
		return $this->redirect($this->generateUrl('agence'));
		
	}

}