<?php
namespace CAF\BlocBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CAF\BlocBundle\Entity\BlocMenu;
use CAF\BlocBundle\Entity\Bloc;
use CAF\BlocBundle\Form\BlocMenuType;
use CAF\BlocBundle\Form\BlocLabelPopupType;

use Doctrine\DBAL\DriverManager;

/**
 * @Route("/bloc")
 */
class BlocController extends Controller
{

	private function generateBlocs($blocs){
		//var_dump($blocs);die();
		if(!empty($blocs)) {
			$position = $blocs[0]['position'];
			foreach ($blocs as $key => $bloc) {
				if($bloc['position']==$position){
					$blocs_return[$position][] = $bloc;
				}else{
					$position=$bloc['position'];
					$blocs_return[$position][] = $bloc;
				}
			}
			return $blocs_return;
		}
		return null;
	}

	private function getOrdre($bloc){
		$er = $this->getDoctrine()
		        ->getRepository('CAFBlocBundle:Bloc');
		$ordre = $er->getMaxOrdre($bloc->getPosition());
		$ordre = $ordre[1];

		if($ordre==null){
			$bloc->setOrdre(1);
		}else{
			$ordre ++;
			$bloc->setOrdre($ordre);
		}

		return $bloc;
	}

	/**
	 * @Route("/blocs/{tab_id}", name="blocs", defaults={"page"="1", "nb_elem"="20", "tab_id"="null"})
	 * @Template()
	 */
	public function indexAction($page,$nb_elem,$tab_id)
	{

		//var_dump($tab_id);die();
		$session = $this->get('session');
		$session->set('bloc_type','');
		$blocs = $this->getDoctrine()
		        ->getRepository('CAFBlocBundle:Bloc')
		        ->findAllOrder($page,$nb_elem);

		$blocs = $this->generateBlocs($blocs);

		$pagination = $this->getDoctrine()
		        ->getRepository('CAFBlocBundle:Bloc')
		        ->getPagination($nb_elem);

		$bloc = new Bloc();		
		$form = $this->createForm(new BlocLabelPopupType(), $bloc);						
						
		if(!$blocs)
			return array('blocs' => null, 'form' => $form->createView());

		return array('blocs' => $blocs, 'form' => $form->createView(), 'nb_pages' => $pagination, 'nb_elem' => $nb_elem, 'pagination' => $pagination, 'link' => '', 'tab_id' => $tab_id);
	}

	/**
	 * @Route("/new", name="new_bloc")
	 * @Template()
	 **/
	public function newAction(Request $request)
	{
		$session = $this->get('session');
		if(($session->get('bloc_type','')=='')){
			if($request->getMethod() != 'POST' && $session->get('bloc_type')!=$request->get('bloc_popup')){
				$session->set('bloc_type', $request->get('bloc_popup'));
			}
		}

		$bloc_popup = $session->get('bloc_type');
		$blocentity = "CAF\BlocBundle\Entity\\".$bloc_popup['type'];
		$bloctype = "CAF\BlocBundle\Form\\".$bloc_popup['type'].'Type';

		//var_dump($bloc_popup);
		$bloc = new $blocentity;
		//var_dump($bloc);die();
		$form = $this->createForm(new $bloctype, $bloc);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$data = $form->getData();	
				$bloc_base = $bloc->getBloc();
				$bloc_base->setType($bloc->getType());
				$bloc_base->addBloc($bloc);	

				$bloc_base = $this->getOrdre($bloc_base);

				//var_dump($bloc->getBlocs());die();
				$em = $this->getDoctrine()->getEntityManager();

        		
			    $em->persist($bloc_base);
			    $em->persist($bloc);
			    $em->flush();
			    $session->set('bloc_type', '');


				$json['bloc_type'] = $bloc->getType();
				$json['bloc_id'] = $bloc->getId();
				$json = json_encode($json);
				$bloc_base->setParams($json);
				$em->persist($bloc_base);
				$em->flush();

				//var_dump($json);die();

			    $this->get('session')->setFlash('success', 'New bloc were saved!');

				return $this->redirect($this->generateUrl('blocs'));
			}
		}
		return array('form' => $form->createView(), 'id' => 0, 'bloc_type' => $bloc_popup['type']);
	}

	/**
	 * @Route("/edit/{id}", name="edit_bloc")
	 * @Template("CAFBlocBundle:Bloc:new.html.twig")
	 **/
	public function editAction(Request $request, $id)
	{
		$bloc_base =  $this->getDoctrine()
					        ->getRepository('CAFBlocBundle:Bloc')
					        ->find($id);
		$params = json_decode($bloc_base->getparams());
		$classname = $params->bloc_type;
		$bloctype="CAF\BlocBundle\Form\\".$classname.'Type';
		$bloc_id = $params->bloc_id;
		$bloc = $this->getDoctrine()
				        ->getRepository('CAFBlocBundle:'.$classname)
				        ->find($bloc_id);
		$form = $this->createForm(new $bloctype, $bloc);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$data = $form->getData();	
				$bloc_base = $bloc->getBloc();
				$bloc_base->setType($bloc->getType());
				$bloc_base->addBloc($bloc);	
				//var_dump($bloc->getBlocs());die();
				$em = $this->getDoctrine()->getEntityManager();
			    $em->persist($bloc_base);
			    $em->persist($bloc);
			    $em->flush();


				$json['bloc_type'] = $bloc->getType();
				$json['bloc_id'] = $bloc->getId();
				$json = json_encode($json);
				$bloc_base->setParams($json);
				$em->persist($bloc_base);
				$em->flush();

				//var_dump($json);die();

			    $this->get('session')->setFlash('success', 'New bloc were saved!');

				return $this->redirect($this->generateUrl('blocs'));
			}
		}
		return array('form' => $form->createView(), 'id' => $id, 'bloc_type' => $classname);
	}

	/**
	 * @Route("/remove/{id}", name="remove_bloc")
	 * @Template()
	 **/
	public function removeAction(Request $request, $id)
	{
		$bloc_base =  $this->getDoctrine()
					        ->getRepository('CAFBlocBundle:Bloc')
					        ->find($id);
		$params = json_decode($bloc_base->getparams());
		$classname = $params->bloc_type;
		$bloctype="CAF\BlocBundle\Form\\".$classname.'Type';
		$bloc_id = $params->bloc_id;
		$bloc = $this->getDoctrine()
				        ->getRepository('CAFBlocBundle:'.$classname)
				        ->find($bloc_id);

                
		$em = $this->getDoctrine()->getEntityManager();
                $blocs = $this->getDoctrine()
				        ->getRepository('CAFBlocBundle:Bloc')
				        ->getRemoveBloc($bloc_base->getOrdre());
                
                foreach($blocs as $monBloc){

                    $ordre = $monBloc->getOrdre();
                    $ordre--;
                    $monBloc->setOrdre($ordre);
                    
                    $em->persist($monBloc);
                    $em->flush();		 
                }
                
                $em->remove($bloc_base);
                $em->remove($bloc);
                $em->flush();		      
		
		return $this->redirect($this->generateUrl('blocs'));
	}


	/**
	 * @Route("/published/", name="publish_bloc")
	 */
	public function pulishBlocAction(Request $request) {
		
		$id    = $request->get('id');
		$state = $request->get('state');

		$em = $this->getDoctrine()->getEntityManager();
		$translation = $this->getDoctrine()
					  ->getRepository('CAFBlocBundle:Bloc')
					  ->find($id);
		$translation->setPublished($state);
		$em->persist($translation);
		$em->flush();
		return new Response('');
	}

	/**
	 * @Route("/blocs/ordre/{bloc}/{sens}/{tab_id}", name="changeOrdre")
	 * @Template()
	 */
	public function changeOrdreAction($bloc,$sens,$tab_id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$er = $em->getRepository('CAFBlocBundle:Bloc');

		if($sens == 'UP')
			$er->moveUp($bloc,1);
		else
			$er->moveDown($bloc,1);
		//var_dump($this->generateUrl('blocs', array('tab_id' => $tab_id)));die();
		
		return $this->redirect($this->generateUrl('blocs', array('tab_id' => $tab_id)));
	}


}	