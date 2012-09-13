<?php

namespace CAF\CRMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CAF\CRMBundle\Entity\FormulaireCRM;
use CAF\CRMBundle\Entity\HistoStatut;
use CAF\CRMBundle\Form\FormulaireCRMType;
use CAF\CRMBundle\Form\HistoStatutType;
use CAF\CRMBundle\Form\HistoEmailType;
/**
 * @Route("/formulaireCRM")
 */
class FormulaireCRMController extends Controller
{
	
	/**
	 * @Route("/", name="formulaireCRM")
	 * @Template()
	 **/
	public function indexAction()
	{
		$formulaires = $this->getDoctrine()
		        ->getRepository('CAFCRMBundle:FormulaireCRM')
		        ->findAll();

		if (!$formulaires) {
        	return array('formulaires' => null);
    	}
		return array('formulaires' => $formulaires);
	}

	/**
	 * @Route("/edit/{id}", name="edit_formulaireCRM")
	 * @Template()
	 */
	public function editFormulaireCRMAction(Request $request,$id) {
		$formulaire = $this->getDoctrine()
					 ->getRepository('CAFCRMBundle:FormulaireCRM')
					 ->find($id);	
				 
		//on recupere deux formulaires : celui du statut et celui de l'envoi de mail
		$formHistoStatut = $this->createForm(new HistoStatutType(),$formulaire->getCurrentStatut());
		$formHistoEmail = $this->createForm(new HistoEmailType(),$formulaire->getCurrentTypeEmail());

		//On récupere les historiques des statuts et des email
		$em = $this->getDoctrine()->getEntityManager();
		if($formulaire->getCurrentStatut()->getIdParent()){
			$qb = $em->createQueryBuilder();
	        $qb->select('histo')
	         ->from('CAFCRMBundle:HistoStatut','histo')
	         ->where("(histo.id_parent = :id_parentstatut OR histo.id = :id_parentstatut)")
	         ->orderBy('histo.dateStatutMAJ', 'DESC')
	         ->setParameter('id_parentstatut', $formulaire->getCurrentStatut()->getIdParent());
	         //->setParameter('id_currentstatut', $formulaire->getCurrentStatut()->getId());
	         
	        $query = $qb->getQuery();   

	        $historiqueStatut = $query->getResult();
		}
		else{
			$historiqueStatut = array();
		}

		if($formulaire->getCurrentTypeEmail()->getDateEnvoi()!=null){
			$qb = $em->createQueryBuilder();
	        $qb->select('histoE')
	         ->from('CAFCRMBundle:HistoEmail','histoE')
	         ->where("(histoE.id_parent = :id_parentemail OR histoE.id = :id_parentemail)")
	         ->orderBy('histoE.dateEnvoi', 'DESC')
	         ->setParameter('id_parentemail', $formulaire->getCurrentTypeEmail()->getIdParent());
	         
	        $query = $qb->getQuery();   

	        $historiqueEmail = $query->getResult();
		}
		else{
			$historiqueEmail = array();
		}

		return array( 'formHistoStatut' => $formHistoStatut->createView(), 'formHistoEmail' => $formHistoEmail->createView(), 'id' => $id,'formulaire'=>$formulaire,'listeHistoEmail'=>$historiqueEmail,'listeHistoStatut'=>$historiqueStatut);
	}

	/**
	 * @Route("/savehistostatut/{id}", name="save_HistoStatut")
	 * @Template()
	 */
	public function saveHistoStatutAction(Request $request,$id){
		$formulaire = $this->getDoctrine()
					 ->getRepository('CAFCRMBundle:FormulaireCRM')
					 ->find($id);	

		$formHistoStatut = $this->createForm(new HistoStatutType(),$formulaire->getCurrentStatut());
		
		if ($request->getMethod() == 'POST') {
			$em = $this->getDoctrine()->getEntityManager();
			$statutstart= $formulaire->getCurrentStatut()->getStatutDemande()->getLibelle(); 
			$formHistoStatut->bindRequest($request);

			$statutsave= $formulaire->getCurrentStatut()->getStatutDemande()->getLibelle();
			
			if($statutstart!=$statutsave){
				$oldStatut = $formulaire->getCurrentStatut();
				$newStatut = $oldStatut->cloneStatut($oldStatut);
				$newStatut = $newStatut->setUser($this->getUser());
				$em->detach($oldStatut); 
				$newStatut = $em->merge($newStatut);
				$formulaire->setCurrentStatut($newStatut);										
			}
			else{
				$formulaire->getCurrentStatut()->setUser($this->getUser());
			}

			$em->persist($formulaire);
			$em->flush();
			$this->get('session')->setFlash('success', 'Le formulaire a bien été sauvegardé');
			
		}

		
		return $this->redirect($this->generateUrl('formulaireCRM'));
	}

	/**
	 * @Route("/savehistotypeemail/{id}", name="save_HistoTypeEmail")
	 * @Template()
	 */
	public function saveHistoTypeEmailAction(Request $request,$id){
		$formulaire = $this->getDoctrine()
					 ->getRepository('CAFCRMBundle:FormulaireCRM')
					 ->find($id);	

		$formHistoEmail = $this->createForm(new HistoEmailType(),$formulaire->getCurrentTypeEmail());
		
		if ($request->getMethod() == 'POST') {
			$em = $this->getDoctrine()->getEntityManager();
			$formHistoEmail->bindRequest($request);
			$oldStatut = $formulaire->getCurrentTypeEmail();
			if($formulaire->getCurrentTypeEmail()->getDateEnvoi()!=null){				
				$newStatut = $oldStatut->cloneTypeEmail($oldStatut);
				$newStatut = $newStatut->setUser($this->getUser());
				$em->detach($oldStatut); 
				$newStatut = $em->merge($newStatut);
				$formulaire->setCurrentTypeEmail($newStatut);	
			}
			else{
				$now = date('Y-m-d G:i:s');
				$oldStatut->setDateEnvoi(new \DateTime($now));
				$oldStatut->setUser($this->getUser());
			}

			$em->persist($formulaire);
			$em->flush();

			$envoimail = \Swift_Message::newInstance()
		        ->setSubject($formulaire->getCurrentTypeEmail()->getSujet())
		        ->setFrom($formulaire->getCurrentTypeEmail()->getEmailEnvoyeur())
		        ->setTo($formulaire->getCurrentTypeEmail()->getEmailClient())
		        ->setBody($this->renderView('CAFCRMBundle:FormulaireCRM:message.html.twig', array('message' => $formulaire->getCurrentTypeEmail()->getMessage())))
		        ->setContentType("text/html")
		    ;
		    $this->get('mailer')->send($envoimail);
			$this->get('session')->setFlash('success', 'L\'email a bien été envoyé');
			
		}
		
		return $this->redirect($this->generateUrl('edit_formulaireCRM', array('id' =>$id)));
	}


	/**
	 * @Route("/entry/getcontentmail", name="get_contentmail")
	 * @Template("CAFCRMBundle:FormulaireCRM:message.html.twig")
	 */

	public function getContentMailAction(Request $request)
	{               
		
	    if($request->isXmlHttpRequest())
	    {
	     	$id_typeemail = 0;
	        $id_typeemail = $request->request->get('idtypeemail');

	        $em = $this->container->get('doctrine')->getEntityManager();

	        if($id_typeemail)
	        {
	               $qb = $em->createQueryBuilder();

	               $qb->select('m.message')
	                 ->from('CAFCRMBundle:TypeEmail','m')
	                 ->where("m.id = :id_typeemail")
	                 ->setParameter('id_typeemail', $id_typeemail);

	               $query = $qb->getQuery();               
	               $message = $query->getResult();
	               return $this->container->get('templating')->renderResponse('CAFCRMBundle:FormulaireCRM:message.html.twig', array(
		            'message' =>  $message[0]['message']
		            ));
	        }     
	       
	    }
	   	return null;
	}

	/**
	 * @Route("/entry/getagenceregionale", name="get_agenceregionale")
	 * @Template("CAFCRMBundle:FormulaireCRM:selectagence.html.twig")
	 */

	public function getAgenceRegionaleAction(Request $request)
	{               
		
	    if($request->isXmlHttpRequest())
	    {
	     	$id_caisseregionale = 1;
	        $id_caisseregionale = $request->request->get('idcaisseregionale');

	        $em = $this->container->get('doctrine')->getEntityManager();

	        if($id_caisseregionale)
	        {
	               $qb = $em->createQueryBuilder();

	               $qb->select('m')
	                 ->from('CAFCRMBundle:AgenceCaisseRegionale','m')
	                 ->where("m.caisseRegional = :id_caisseregionale")
	                 ->setParameter('id_caisseregionale', $id_caisseregionale);

	               $query = $qb->getQuery();               
	               $message = $query->getResult();
	               return $this->container->get('templating')->renderResponse('CAFCRMBundle:FormulaireCRM:selectagence.html.twig', array(
		            'message' =>  $message
		            ));
	        }     
	       
	    }
	   	return null;
	}

}