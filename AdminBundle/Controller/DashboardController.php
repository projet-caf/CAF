<?php

namespace CAF\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CAF\AdminBundle\Entity\Redirect;
use CAF\AdminBundle\Form\RedirectType;

/**
 * @Route("/dashboard")
 */
class DashboardController extends Controller
{

	/**
	 * @Route("/", name="dashboard")
	 * @Template()
	 */
	public function indexAction()
	{
		$articles = $this->getDoctrine()
						 ->getRepository('CAFContentBundle:Content')
						 ->lastArticles(5);

		$redirects = $this->getDoctrine()
						  ->getRepository('CAFFrontBundle:ErrorUrl')
						  ->findAll();
		return array('articles' => $articles, 'redirects' => $redirects);
	}

	/**
	 * @Route("/edit/{id}", name="edit_redirect")
	 * @Template()
	 */
	public function editAction(Request $request,$id)
	{
		$redirect = $this->getDoctrine()
					  ->getRepository('CAFAdminBundle:Redirect')
					  ->find($id);

		$form = $this->createForm(new RedirectType(), $redirect);
		if ($request->getMethod() == 'POST') {

			$form->bindRequest($request);

			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
			   	$em->persist($redirect);
			    $em->flush();

			    $this->get('session')->setFlash('success', 'La redirection a été prise en compte');
				return $this->redirect($this->generateUrl('dashboard'));
			}
		}
		return array('form' => $form->createView(), 'id' => $id);			  
	}
}