<?php

namespace CAF\ContentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CAF\ContentBundle\Entity\Metas;
use CAF\ContentBundle\Form\MetasType;

/**
 * @Route("/metas")
 */
class MetasController extends Controller
{

	/**
	 * @Route("/", name="metas")
	 * @Template()
	 **/
	public function indexAction() {
		$metas = $this->getDoctrine()
		        ->getRepository('CAFContentBundle:Metas')
		        ->findAll();

		if (!$metas) {
        	return array('metas' => null);
    	}
		return array('metas' => $metas);
	}


	/**
	 * @Route("/new", name="new_metas")
	 * @Template()
	 **/
	public function newAction(Request $request) {

		$metas = new Metas();
		$form = $this->createForm(new MetasType(), $metas);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
			    $em->persist($metas);
			    $em->flush();

			     $this->get('session')->setFlash('success', 'New meta were saved!');

				return $this->redirect($this->generateUrl('metas'));
			}
		}
		return array('form' => $form->createView());
	}

	/**
	 * @Route("/edit/{id}", name="edit_metas")
	 * @Template("CAFContentBundle:Metas:new.html.twig")
	 **/
	public function editAction($id,Request $request) {

		$metas = $this->getDoctrine()
		        ->getRepository('CAFContentBundle:Metas')
		        ->find($id);
		$form = $this->createForm(new MetasType(), $metas);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
			    $em->persist($metas);
			    $em->flush();
			    $this->get('session')->setFlash('success', 'New meta edited!');
				return $this->redirect($this->generateUrl('metas'));
			}
		}
		return array('form' => $form->createView(), 'id' => $id);
	}
}