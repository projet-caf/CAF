<?php

namespace CAF\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CAF\CRMBundle\Entity\Conseiller;
use CAF\CRMBundle\Form\ConseillerType;

/**
 * @Route("/conseiller")
 */
class ConseillerController extends Controller
{
	
	/**
	 * @Route("/", name="conseiller")
	 * @Template()
	 **/
	public function indexAction()
	{
		$conseillers = $this->getDoctrine()
		        ->getRepository('CAFCRMBundle:Conseiller')
		        ->findAll();

		if (!$conseillers) {
        	return array('conseillers' => null);
    	}
		return array('conseillers' => $conseillers);
	}
	
	/**
	 * @Route("/new", name="new_conseiller")
	 * @Template()
	 **/
	public function newConseillerAction(Request $request)
	{
		$conseiller = new Conseiller();
		$form = $this->createForm(new ConseillerType(), $conseiller);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {

				$em = $this->getDoctrine()->getEntityManager();							
			    $em->persist($conseiller);
			    $em->flush();
			    $this->get('session')->setFlash('success', 'Le nouveau conseiller a bien été sauvegardée!');
				return $this->redirect($this->generateUrl('conseiller'));

			}
		}
		/*$agences = $this->getDoctrine()
		        ->getRepository('CAFCRMBundle:Agence')
		        ->findAll();*/
		//return array('form' => $form->createView(),'agences' => $agences);
		return array('form' => $form->createView());
		
	}

	/**
	 * @Route("/edit/{id}", name="edit_conseiller")
	 * @Template("CAFCRMBundle:Conseiller:newConseiller.html.twig")
	 */
	public function editConseillerAction(Request $request,$id) {
		$conseiller = $this->getDoctrine()
					 ->getRepository('CAFCRMBundle:Conseiller')
					 ->find($id);

		$form = $this->createForm(new ConseillerType(),$conseiller);
		
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($conseiller);
				$em->flush();
				$this->get('session')->setFlash('success', 'Le conseiller a bien été éditée');
				return $this->redirect($this->generateUrl('conseiller'));
			}
		}
		return array('form' => $form->createView(), 'id' => $id);
	}

	
	/**
	 * @Route("/published/", name="publish_conseiller")
	 */
	public function pulishFieldAction(Request $request) {
		
		$id    = $request->get('id');
		$state = $request->get('state');

		$em = $this->getDoctrine()->getEntityManager();
		$field = $this->getDoctrine()
					  ->getRepository('CAFCRMBundle:Conseiller')
					  ->find($id);
		$field->setPublished($state);
		$em->persist($field);
		$em->flush();
		return new Response('');
	}

	 /**
	 * @Route("/action", name="action_conseiller")
	 * @Template()
	 */
	public function actionConseillerAction(Request $request) {
		$listid=$request->request->get('listid');
		$selectaction=$request->request->get('selectaction');
		$erreur=0;
		if(count($listid) && $request->getMethod() == 'POST'){
			$em = $this->getDoctrine()->getEntityManager();
			foreach ($listid as $id) {
				$conseiller = $this->getDoctrine()
					 ->getRepository('CAFCRMBundle:Conseiller')
					 ->find($id);
				if (is_object($conseiller)) {					
					if($selectaction=="unpublish"){
						$conseiller->setPublished(0);
						$em->persist($conseiller);
						$em->flush();
					}
					elseif($selectaction=="publish"){
						$conseiller->setPublished(1);
						$em->persist($conseiller);
						$em->flush();
					}
				}
			}
			$this->get('session')->setFlash('success', 'L\'action choisie a bien été réalisée');		
		}
		return $this->redirect($this->generateUrl('conseiller'));
		
	}

}