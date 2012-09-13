<?php

namespace CAF\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CAF\AdminBundle\Entity\VarGlobal;
use CAF\AdminBundle\Form\VarGlobalType;

/**
 * @Route("/varGlobal")
 */
class VarGlobalController extends Controller
{
	
	/**
	 * @Route("/", name="varGlobal")
	 * @Template()
	 **/
	public function indexAction()
	{
		$varsglobals = $this->getDoctrine()
		        ->getRepository('CAFAdminBundle:VarGlobal')
		        ->findAll();

		if (!$varsglobals) {
        	return array('varsglobals' => null);
    	}
		return array('varsglobals' => $varsglobals);
	}
	
	/**
	 * @Route("/new", name="new_varGlobal")
	 * @Template()
	 **/
	public function newVarGlobalAction(Request $request)
	{
		$varglobal = new VarGlobal();
		$form = $this->createForm(new VarGlobalType(), $varglobal);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {

				$em = $this->getDoctrine()->getEntityManager();							
			    $em->persist($varglobal);
			    $em->flush();
			    $this->get('session')->setFlash('success', 'La nouvelle variable globale a bien été sauvegardée!');
				return $this->redirect($this->generateUrl('varGlobal'));

			}
		}
		return array('form' => $form->createView());
		
	}

	/**
	 * @Route("/edit/{id}", name="edit_varGlobal")
	 * @Template("CAFAdminBundle:VarGlobal:newVarGlobal.html.twig")
	 */
	public function editVarGlobalAction(Request $request,$id) {
		$varglobal = $this->getDoctrine()
					 ->getRepository('CAFAdminBundle:VarGlobal')
					 ->find($id);

		$form = $this->createForm(new VarGlobalType(),$varglobal);
		
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($varglobal);
				$em->flush();
				$this->get('session')->setFlash('success', 'La variable globale a bien été éditée');
				return $this->redirect($this->generateUrl('varGlobal'));
			}
		}
		return array('form' => $form->createView(), 'id' => $id);
	}

	/**
	 * @Route("/delete/{id}", name="delete_varGlobal")
	 * @Template()
	 */
	public function deleteVarGlobalAction(Request $request,$id) {
		$varglobal = $this->getDoctrine()
					 ->getRepository('CAFAdminBundle:VarGlobal')
					 ->find($id);		 
		if (!is_object($varglobal)) {
			$this->get('session')->setFlash('error', 'La variable globale ne peut pas être supprimée');
			return $this->redirect($this->generateUrl('varGlobal'));
		}

		$em = $this->getDoctrine()->getEntityManager();
		$em->remove($varglobal);
		$em->flush();
		$this->get('session')->setFlash('success', 'La variable globale a bien été supprimée');
		return $this->redirect($this->generateUrl('varGlobal'));
	}
	

	/**
	 * @Route("/published/", name="publish_field")
	 */
	public function pulishFieldAction(Request $request) {
		
		$id    = $request->get('id');
		$state = $request->get('state');

		$em = $this->getDoctrine()->getEntityManager();
		$field = $this->getDoctrine()
					  ->getRepository('CAFAdminBundle:VarGlobal')
					  ->find($id);
		$field->setPublished($state);
		$em->persist($field);
		$em->flush();
		return new Response('');
	}

	 /**
	 * @Route("/action", name="action_varGlobal")
	 * @Template()
	 */
	public function actionVarGlobalAction(Request $request) {
		$listid=$request->request->get('listid');
		$selectaction=$request->request->get('selectaction');
		$erreur=0;
		if(count($listid) && $request->getMethod() == 'POST'){
			$em = $this->getDoctrine()->getEntityManager();
			foreach ($listid as $id) {
				$varglobal = $this->getDoctrine()
					 ->getRepository('CAFAdminBundle:VarGlobal')
					 ->find($id);
				if (is_object($varglobal)) {					
					if($selectaction=="delete"){						
						$em->remove($varglobal);
						$em->flush();
					}
					elseif($selectaction=="unpublish"){
						$varglobal->setPublished(0);
						$em->persist($varglobal);
						$em->flush();
					}
					elseif($selectaction=="publish"){
						$varglobal->setPublished(1);
						$em->persist($varglobal);
						$em->flush();
					}
				}
			}
			$this->get('session')->setFlash('success', 'L\'action choisie a bien été réalisée');		
		}
		return $this->redirect($this->generateUrl('varGlobal'));
		
	}

}