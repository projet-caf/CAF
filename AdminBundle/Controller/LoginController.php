<?php

namespace CAF\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class LoginController extends Controller
{
	
	/**
	 * @Route("/login", name="Login")
	 * @Template() 
	 */
	public function loginAction()
	{
		$request = $this->getRequest();
		$session = $request->getSession();

       
    	        
		// get the login error if there is one
		if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
		    $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
		} else {
		    $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
		    $session->remove(SecurityContext::AUTHENTICATION_ERROR);

			
		}
		

		return $this->render('CAFAdminBundle:Login:login.html.twig', array(
		    // last username entered by the user
		    'last_username' => $session->get(SecurityContext::LAST_USERNAME),
		    'error'         => $error,
		));
	}
	
	/**
	 * @Route("/login_check", name="login_check")
	 *
	 */
	public function loginCheckAction()
	{
		return array();	
	}
	
	/**
	 * @Route("/logout", name="logout")
	 *
	 */
	public function logoutAction()
	{
		return array();	
	}
	
}