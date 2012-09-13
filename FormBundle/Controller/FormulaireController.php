<?php

namespace CAF\FormBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Collections\ArrayCollection;

use CAF\FormBundle\Entity\Formulaire;
use CAF\FormBundle\Entity\FormTaxonomy;
use CAF\FormBundle\Entity\FormFieldsValue;

use CAF\FormBundle\Form\FormTaxonomyPopupType;
use CAF\FormBundle\Form\FormulaireType;

use CAF\FormBundle\Loaders\ContentLoader;
use CAF\FormBundle\Configuration\ConfigurationUpload;

use Doctrine\DBAL\DriverManager;


/**
 * @Route("/formulaire")
 */
class FormulaireController extends Controller
{

	/**
	 * @Route("/formulaires/{page}/{nb_elem}/{taxonomy}", name="formulaire", defaults={"page"="1", "nb_elem"="-1", "taxonomy"="-1"})
	 * @Template()
	 */
	public function indexAction($page,$nb_elem,$taxonomy)
	{	

		if($nb_elem > 0) {
			$formulaires = $this->getDoctrine()
						->getRepository('CAFFormBundle:Formulaire')
						->findAllOrder($page,$nb_elem,$taxonomy);
		} else {
			$formulaires = $this->getDoctrine()
						->getRepository('CAFFormBundle:Formulaire')
						->findAllOrder($page,-1,$taxonomy);
		}
		
		$form_taxonomy = $this->getDoctrine()
								 ->getRepository('CAFFormBundle:FormTaxonomy')
								 ->findAll();			

		$content = new Formulaire();
		$form = $this->createForm(new FormTaxonomyPopupType(), $content);
		
		$pagination = $this->getDoctrine()
		        		   ->getRepository('CAFFormBundle:Formulaire')          
		        		   ->getPagination($nb_elem);				
		
		if(!$formulaires)
			return array('formulaires' => null, 'form' => $form->createView(), 'form_taxonomy' => $form_taxonomy,'nb_pages' => $pagination, 'nb_elem' => $nb_elem, 'link' => '', 'taxonomy' => $taxonomy);

		return array('formulaires' => $formulaires,'nb_pages' => $pagination, 'nb_elem' => $nb_elem, 'form' => $form->createView(), 'link' => '', 'form_taxonomy' => $form_taxonomy, 'taxonomy' => $taxonomy);
	
	}

	/**
	 * @Route("/new", name="new_formulaire")
	 * @Template("CAFFormBundle:Formulaire:new.html.twig")
	 */
	public function newAction(Request $request)
	{
		$formulaire = new Formulaire();	
		$form_taxonomy_popup = $request->get('form_taxonomy_popup');
		$id_form_taxonomy = $form_taxonomy_popup['id_form_taxonomy'];
		$form = $this->createForm(new FormulaireType(), $formulaire,array('form_taxonomy' => $id_form_taxonomy));

		if ($request->getMethod() == 'POST') {

			$form->bindRequest($request);
			$form_request = $request->get('formulaire');
			$form_taxonomy = $this->getDoctrine()
								 ->getRepository('CAFFormBundle:FormTaxonomy')
								 ->find($request->get('form_taxonomy'));
			$formulaire->setIdFormTaxonomy($form_taxonomy);
			
			//if ($form->isValid()) {
				
				$em = $this->getDoctrine()->getEntityManager();							
				$em->persist($formulaire);
			    $em->flush();

			    if(isset($form_request['values']))
					$values = $form_request['values'];
				else
					$values = array();

				$field_repository = $this->getDoctrine()->getRepository('CAFFormBundle:FormFields');
				$formfieldsvars=$form_taxonomy->getFormFields();
											
				foreach ($formfieldsvars as $formfieldsvar) {
					
					if(isset($values[$formfieldsvar->getName()])) {
						$new_value= new FormFieldsValue();
						$value = $values[$formfieldsvar->getName()];
						$new_value->setValue($value);
						$new_value->setFormfield($formfieldsvar);						
						$new_value->setFormulaire($formulaire);
						$formulaire->addFormfieldsvalue($new_value);
					}				
				}				
			    $em->persist($formulaire);
			    $em->flush();

			    $this->get('session')->setFlash('success', 'La nouveau formulaire a bien été sauvegardé!');
				
				return $this->redirect($this->generateUrl('formulaire'));

				
			//}
		}	
		return array('form' => $form->createView(), 'taxonomy' => $id_form_taxonomy);
	
	
	}

	/**
	 * @Route("/edit/{id}", name="edit_formulaire")
	 * @Template("CAFFormBundle:Formulaire:new.html.twig")
	 */
	public function editAction(Request $request,$id)
	{
		$formulaire = $this->getDoctrine()
					 ->getRepository('CAFFormBundle:Formulaire')
					 ->find($id);		

		
		$id_form_taxonomy = intval($formulaire->getIdFormTaxonomy()->getId());

		$varfields = $formulaire->getFormfieldsvalue();

		$repository_field = $this->getDoctrine()->getRepository('CAFFormBundle:FormFieldsValue');
		$fieldsvalue = $repository_field->getValues();
		
		$values = array();

		foreach ($fieldsvalue as $fieldvalue) {
			if ($fieldvalue->getValue() != '')
				$temp_value = $fieldvalue->getValue();
			else
				$temp_value = '';

			if(is_array($temp_value) && array_key_exists('alt', $temp_value)) {
				$values[$fieldvalue->getFormField()->getName()]['alt'] = $temp_value['alt'];
				if(array_key_exists('title', $temp_value)) {
					$values[$fieldvalue->getFormField()->getName()]['title'] = $temp_value['title'];
				} else {
					$values[$fieldvalue->getFormField()->getName()]['title'] = '';
				}
				if(array_key_exists('image', $temp_value)) {
					$values[$fieldvalue->getFormField()->getName()]['image'] = $temp_value['image'];
				} else {
					$values[$fieldvalue->getFormField()->getName()]['image'] ='';
				}
			} else {
				$values[$fieldvalue->getFormField()->getName()] = $temp_value;	
			}
		}
		$formulaire->setValues($values);
		$form = $this->createForm(new FormulaireType(),$formulaire,  array('form_taxonomy' => $id_form_taxonomy));
		
		
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);

			$form_request = $request->get('formulaire');
              
			$em = $this->getDoctrine()->getEntityManager();
				
			$form_taxonomy = $formulaire->getIdFormTaxonomy(); 	
			
		    if(isset($form_request['values']))
				$values = $form_request['values'];
			else
				$values = array();

			//On récupère les valeurs renseignées dans le formulaire
			$old_values= $formulaire->getFormfieldsvalue();

			$field_repository = $this->getDoctrine()->getRepository('CAFFormBundle:FormFields');
			$formfieldsvars=$form_taxonomy->getFormFields();
			foreach ($formfieldsvars as $formfieldsvar) {
				if(isset($values[$formfieldsvar->getName()])) {
					//on parcours toutes les valeurs du formulaire pour mettre à jour la bonne					
					foreach ($old_values as $old_value) {
						//on compare les noms des valeurs
						if($old_value->getFormfield()->getName()==$formfieldsvar->getName()){
							$value = $values[$formfieldsvar->getName()];
							$old_value->setValue($value);
						}
					}
					
				}				
			}			
		    $em->persist($formulaire);
		    $em->flush();

		    $this->get('session')->setFlash('success', 'Le formulaire a bien été sauvegardé!');
			
			return $this->redirect($this->generateUrl('formulaire'));
			
		}
		return array('form' => $form->createView(), 'taxonomy' => $id_form_taxonomy, 'id' => $id);
	}

	/**
	 * @Route("/delete/{id}", name="delete_formulaire")
	 * @Template()
	 */
	public function deleteAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$formulaire = $this->getDoctrine()
						->getRepository('CAFFormBundle:Formulaire')
						->find($id);				

		if (!$formulaire) {
			$this->get('session')->setFlash('error', "Ce formulaire n\'existe pas!");
			return $this->redirect($this->generateUrl('formulaire'));
		}

	
		$fieldsvalue = $formulaire->getFormFieldsvalue();
		foreach ($fieldsvalue as $value) {
			$em->remove($value);
		}

		$em->remove($formulaire);
		$em->flush();
		$this->get('session')->setFlash('success', 'le formulaire a bien été supprimé');
		
		return $this->redirect($this->generateUrl('formulaire'));
	}





}