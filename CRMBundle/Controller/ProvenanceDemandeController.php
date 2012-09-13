<?php

namespace CAF\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CAF\CRMBundle\Entity\ProvenanceDemande;
use CAF\CRMBundle\Form\ProvenanceDemandeType;

/**
 * @Route("/provenanceDemande")
 */
class ProvenanceDemandeController extends Controller
{
	
	/**
	 * @Route("/", name="provenanceDemande")
	 * @Template()
	 **/
	public function indexAction()
	{
		$provenances = $this->getDoctrine()
		        ->getRepository('CAFCRMBundle:ProvenanceDemande')
		        ->findAll();

		if (!$provenances) {
        	return array('provenances' => null);
    	}
		return array('provenances' => $provenances);
	}
	
	/**
	 * @Route("/new", name="new_provenanceDemande")
	 * @Template()
	 **/
	public function newProvenanceDemandeAction(Request $request)
	{
		$provenance = new ProvenanceDemande();
		$form = $this->createForm(new ProvenanceDemandeType(), $provenance);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {

				$em = $this->getDoctrine()->getEntityManager();							
			    $em->persist($provenance);
			    $em->flush();
			    $this->get('session')->setFlash('success', 'La nouvelle provenance de la demande a bien été sauvegardé!');
				return $this->redirect($this->generateUrl('provenanceDemande'));

			}
		}
		return array('form' => $form->createView());
		
	}

	/**
	 * @Route("/edit/{id}", name="edit_provenanceDemande")
	 * @Template("CAFCRMBundle:ProvenanceDemande:newProvenanceDemande.html.twig")
	 */
	public function editProvenanceDemandeAction(Request $request,$id) {
		$provenance = $this->getDoctrine()
					 ->getRepository('CAFCRMBundle:ProvenanceDemande')
					 ->find($id);

		$form = $this->createForm(new ProvenanceDemandeType(),$provenance);
		
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($provenance);
				$em->flush();
				$this->get('session')->setFlash('success', 'La provenance de la demande a bien été édité');
				return $this->redirect($this->generateUrl('provenanceDemande'));
			}
		}
		return array('form' => $form->createView(), 'id' => $id);
	}

	
	/**
	 * @Route("/published/", name="publish_provenanceDemande")
	 */
	public function pulishFieldAction(Request $request) {
		
		$id    = $request->get('id');
		$state = $request->get('state');

		$em = $this->getDoctrine()->getEntityManager();
		$field = $this->getDoctrine()
					  ->getRepository('CAFCRMBundle:ProvenanceDemande')
					  ->find($id);
		$field->setPublished($state);
		$em->persist($field);
		$em->flush();
		return new Response('');
	}

	 /**
	 * @Route("/action", name="action_provenanceDemande")
	 * @Template()
	 */
	public function actionProvenanceDemandeAction(Request $request) {
		$listid=$request->request->get('listid');
		$selectaction=$request->request->get('selectaction');
		$erreur=0;
		if(count($listid) && $request->getMethod() == 'POST'){
			$em = $this->getDoctrine()->getEntityManager();
			foreach ($listid as $id) {
				$provenance = $this->getDoctrine()
					 ->getRepository('CAFCRMBundle:ProvenanceDemande')
					 ->find($id);
				if (is_object($provenance)) {					
					if($selectaction=="unpublish"){
						$provenance->setPublished(0);
						$em->persist($provenance);
						$em->flush();
					}
					elseif($selectaction=="publish"){
						$provenance->setPublished(1);
						$em->persist($provenance);
						$em->flush();
					}
				}
			}
			$this->get('session')->setFlash('success', 'L\'action choisie a bien été réalisée');		
		}
		return $this->redirect($this->generateUrl('provenanceDemande'));
		
	}

}