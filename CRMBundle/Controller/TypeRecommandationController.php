<?php

namespace CAF\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CAF\CRMBundle\Entity\TypeRecommandation;
use CAF\CRMBundle\Form\TypeRecommandationType;

/**
 * @Route("/typeRecommandation")
 */
class TypeRecommandationController extends Controller
{
	
	/**
	 * @Route("/", name="typeRecommandation")
	 * @Template()
	 **/
	public function indexAction()
	{
		$recommandations = $this->getDoctrine()
		        ->getRepository('CAFCRMBundle:TypeRecommandation')
		        ->findAll();

		if (!$recommandations) {
        	return array('recommandations' => null);
    	}
		return array('recommandations' => $recommandations);
	}
	
	/**
	 * @Route("/new", name="new_typeRecommandation")
	 * @Template()
	 **/
	public function newTypeRecommandationAction(Request $request)
	{
		$recommandation = new TypeRecommandation();
		$form = $this->createForm(new TypeRecommandationType(), $recommandation);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {

				$em = $this->getDoctrine()->getEntityManager();							
			    $em->persist($recommandation);
			    $em->flush();
			    $this->get('session')->setFlash('success', 'La nouvelle recommandation a bien été sauvegardé!');
				return $this->redirect($this->generateUrl('typeRecommandation'));

			}
		}
		return array('form' => $form->createView());
		
	}

	/**
	 * @Route("/edit/{id}", name="edit_typeRecommandation")
	 * @Template("CAFCRMBundle:TypeRecommandation:newTypeRecommandation.html.twig")
	 */
	public function editTypeRecommandationAction(Request $request,$id) {
		$recommandation = $this->getDoctrine()
					 ->getRepository('CAFCRMBundle:TypeRecommandation')
					 ->find($id);

		$form = $this->createForm(new TypeRecommandationType(),$recommandation);
		
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($recommandation);
				$em->flush();
				$this->get('session')->setFlash('success', 'La recommandation a bien été édité');
				return $this->redirect($this->generateUrl('typeRecommandation'));
			}
		}
		return array('form' => $form->createView(), 'id' => $id);
	}

	
	/**
	 * @Route("/published/", name="publish_typeRecommandation")
	 */
	public function pulishFieldAction(Request $request) {
		
		$id    = $request->get('id');
		$state = $request->get('state');

		$em = $this->getDoctrine()->getEntityManager();
		$field = $this->getDoctrine()
					  ->getRepository('CAFCRMBundle:TypeRecommandation')
					  ->find($id);
		$field->setPublished($state);
		$em->persist($field);
		$em->flush();
		return new Response('');
	}

	 /**
	 * @Route("/action", name="action_typeRecommandation")
	 * @Template()
	 */
	public function actionTypeRecommandationAction(Request $request) {
		$listid=$request->request->get('listid');
		$selectaction=$request->request->get('selectaction');
		$erreur=0;
		if(count($listid) && $request->getMethod() == 'POST'){
			$em = $this->getDoctrine()->getEntityManager();
			foreach ($listid as $id) {
				$recommandation = $this->getDoctrine()
					 ->getRepository('CAFCRMBundle:TypeRecommandation')
					 ->find($id);
				if (is_object($recommandation)) {					
					if($selectaction=="unpublish"){
						$recommandation->setPublished(0);
						$em->persist($recommandation);
						$em->flush();
					}
					elseif($selectaction=="publish"){
						$recommandation->setPublished(1);
						$em->persist($recommandation);
						$em->flush();
					}
				}
			}
			$this->get('session')->setFlash('success', 'L\'action choisie a bien été réalisée');		
		}
		return $this->redirect($this->generateUrl('typeRecommandation'));
		
	}

}