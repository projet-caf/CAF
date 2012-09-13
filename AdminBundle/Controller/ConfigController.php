<?php

namespace CAF\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CAF\AdminBundle\Entity\Country;
use CAF\AdminBundle\Entity\Language;

use CAF\AdminBundle\Form\Config\CountryType;
use CAF\AdminBundle\Form\Config\LanguageType;


/**
 * @Route("/config")
 */
class ConfigController extends Controller
{

	/**
	 * @Route("/", name="config")*
	 * @Template()
	 */
	public function indexAction()
	{
		return array('config' => null);
	}

	/**
	 * @Route("/languages", name="languages")
	 * @Template()
	 */
	public function indexLanguageAction()
	{
		$langs = $this->getDoctrine()
					  ->getRepository('CAFAdminBundle:Language')
					  ->findAll();
		if (!$langs) {
			return array('languages' => null);
		}

		return array('languages' => $langs);
	}

	/**
	 * @Route("/countries", name="countries")
	 * @Template()
	 */
	public function indexCountriesAction()
	{
		$countries = $this->getDoctrine()
						  ->getRepository('CAFAdminBundle:Country')
						  ->findAll();
		if (!$countries) {
			return array('countries' => null);
		}

		return array('countries' => $countries);
	}

	/**
	 * @Route("/language/new", name="new_language")
	 * @Template()
	 */
	public function newLanguageAction(Request $request) {
		$lang = new Language();
		$form = $this->createForm(new LanguageType(),$lang);
		
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($lang);
				$em->flush();
				$this->get('session')->setFlash('success', 'Nouvelle langue sauvegardée');
				return $this->redirect($this->generateUrl('languages'));
			}
		}
		return array('form' => $form->createView());
	}

	/**
	 * @Route("/country/new", name="new_country")
	 * @Template()
	 */
	public function newCountryAction(Request $request) {
		$country = new Country();
		$form = $this->createForm(new CountryType(),$country);
		
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($country);
				$em->flush();
				$this->get('session')->setFlash('success', 'Nouveau pays sauvegardé');
				return $this->redirect($this->generateUrl('countries'));
			}
		}
		return array('form' => $form->createView());
	}

	/**
	 * @Route("/language/edit/{id}", name="edit_language")
	 * @Template("CAFAdminBundle:Config:newLanguage.html.twig")
	 */
	public function editLanguageAction(Request $request,$id) {
		$lang = $this->getDoctrine()
					 ->getRepository('CAFAdminBundle:Language')
					 ->find($id);

		$form = $this->createForm(new LanguageType(),$lang);
		
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($lang);
				$em->flush();
				$this->get('session')->setFlash('success', 'La langue a bien été éditée');
				return $this->redirect($this->generateUrl('languages'));
			}
		}
		return array('form' => $form->createView(), 'id' => $id);
	}

	/**
	 * @Route("/country/edit/{id}", name="edit_country")
	 * @Template("CAFAdminBundle:Config:newCountry.html.twig")
	 */
	public function editCountryAction(Request $request,$id) {
		$country = $this->getDoctrine()
					 ->getRepository('CAFAdminBundle:Country')
					 ->find($id);
		$form = $this->createForm(new CountryType(),$country);
		
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($country);
				$em->flush();
				$this->get('session')->setFlash('success', 'Le pays a bien été édité');
				return $this->redirect($this->generateUrl('countries'));
			}
		}
		return array('form' => $form->createView(), 'id' => $id);
	}

	/**
	 * @Route("/language/delete/{id}", name="delete_language")
	 * @Template()
	 */
	public function deleteLanguageAction(Request $request,$id) {
		$lang = $this->getDoctrine()
					 ->getRepository('CAFAdminBundle:Language')
					 ->find($id);

		if (!$lang) {
			$this->get('session')->setFlash('error', 'La langue ne peut pas être supprimée');
			return $this->redirect($this->generateUrl('languages'));
		}

		$em = $this->getDoctrine()->getEntityManager();
		$em->remove($lang);
		$em->flush();
		$this->get('session')->setFlash('success', 'La langue a bien été supprimée');
		return $this->redirect($this->generateUrl('languages'));
	}

	/**
	 * @Route("/country/delete/{id}", name="delete_country")
	 * @Template()
	 */
	public function deleteCountryAction(Request $request,$id) {
		$country = $this->getDoctrine()
					 ->getRepository('CAFAdminBundle:Country')
					 ->find($id);
		
		if (!$country) {
			$this->get('session')->setFlash('error', 'Le pays ne peut pas être supprimé');
			return $this->redirect($this->generateUrl('countries'));
		}

		$em = $this->getDoctrine()->getEntityManager();
		$em->remove($country);
		$em->flush();
		$this->get('session')->setFlash('success', 'Le pays a bien été supprimé');
		return $this->redirect($this->generateUrl('countries'));
		
	}

}