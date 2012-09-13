<?php

namespace CAF\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\User\UserInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CAF\AdminBundle\Entity\User;
use CAF\AdminBundle\Entity\Group;
use CAF\AdminBundle\Form\UserType;
use CAF\AdminBundle\Form\GroupType;

/**
 * @Route("/users")
 */
class UserController extends Controller
{
	
	/**
	 * @Route("/", name="users")
	 * @Template()
	 **/
	public function indexAction()
	{
		$users = $this->getDoctrine()
		        ->getRepository('CAFAdminBundle:User')
		        ->findAll();

		if (!$users) {
        	return array('users' => null);
    	}
		return array('users' => $users);
	}
	
	/**
	 * @Route("/new", name="new_user")
	 * @Template()
	 **/
	public function newUserAction(Request $request)
	{
		$user = new User();
		$form = $this->createForm(new UserType(), $user);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$data = $form->getData();	
				$factory = $this->get('security.encoder_factory');
				$em = $this->getDoctrine()->getEntityManager();
				$encoder = $factory->getEncoder($user);
				$password = $encoder->encodePassword($data->getPassword(), $user->getSalt());
				$user->setPassword($password);
			    $em->persist($user);
			    $em->flush();

			    $this->get('session')->setFlash('success', 'New user saved!');
				return $this->redirect($this->generateUrl('users'));
			}
		}
		return array('form' => $form->createView(), );
	}
	
	/**
	 * @Route("/edit/{id}", name="edit_user")
	 * @Template("CAFAdminBundle:User:newUser.html.twig")
	 */
	public function editAction(Request $request, $id) 
	{
		$user = $this->getDoctrine()
		        ->getRepository('CAFAdminBundle:User')
		        ->find($id);
		$form = $this->createForm(new UserType(), $user);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$factory = $this->get('security.encoder_factory');
			    if (!$user) {
			        throw $this->createNotFoundException('No user found for id '.$id);
			    }

				$em->persist($user);
									
			    $em->flush();
				$this->get('session')->setFlash('success', 'New user edited!');
				return $this->redirect($this->generateUrl('users'));
			}
		}
		return array('form' => $form->createView(), 'id' => $id);
	}

	/**
	 * @Route("/groups", name="groups")
	 * @Template()
	 **/
	public function indexGroupAction()
	{
		$groups = $this->getDoctrine()
		        ->getRepository('CAFAdminBundle:Group')
		        ->findAll();

		if (!$groups) {
        	return array('groups' => null);
    	}
		return array('groups' => $groups);
	}

	/**
	 * @Route("/group/new", name="new_group")
	 * @Template()
	 */
	public function newGroupAction(Request $request) {
		$group = new Group();
		$form = $this->createForm(new GroupType(), $group);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($group);
									
			    $em->flush();
				$this->get('session')->setFlash('success', 'A new group has been added');
				return $this->redirect($this->generateUrl('groups'));
			}
		}
		return array('form' => $form->createView());
	}
	

	/**
	 * @Route("/group/edit/{id}", name="edit_group")
	 * @Template()
	 */
	public function editGroupFunction($id) {
		$group = new Group();
		$form = $this->createForm(new GroupType(), $group);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				
				$em->persist($user);
									
			    $em->flush();
				$this->get('session')->setFlash('success', 'A new group has been added');
				return $this->redirect($this->generateUrl('groups'));
			}
		}
		return array('form' => $form->createView());
	}
	
}