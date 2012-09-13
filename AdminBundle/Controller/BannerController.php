<?php

namespace CAF\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CAF\AdminBundle\Entity\Banner;
use CAF\AdminBundle\Form\BannerType;

/**
 * @Route("/banner")
 */
class BannerController extends Controller
{
	
	/**
	 * @Route("/", name="banner")
	 * @Template()
	 **/
	public function indexAction()
	{
		$banners = $this->getDoctrine()
		        ->getRepository('CAFAdminBundle:Banner')
		        ->findAll();

		if (!$banners) {
        	return array('banners' => null);
    	}
		return array('banners' => $banners);
	}
	
	/**
	 * @Route("/new", name="new_banner")
	 * @Template()
	 **/
	public function newBannerAction(Request $request)
	{
		$banner = new Banner();
		$form = $this->createForm(new BannerType(), $banner);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {				
				$em = $this->getDoctrine()->getEntityManager();		
				$data = $form->getData();	
				
				if($data->getFile()!=''){
					$file= $data->getFile(); 
					$fileName = $file->getClientOriginalName();
					$file->move($data->getAbsolutePath(), $fileName);	
					$banner->setFile($fileName);
				}					
						
				$banner->setAliasCampaignName($data->getAliasCampaignName());				
				$banner->setAliasBannerName($data->getAliasBannerName());
			    $em->persist($banner);
			    $em->flush();
			    $this->get('session')->setFlash('success', 'La bannière a bien été sauvegardée!');
				return $this->redirect($this->generateUrl('banner'));

			}
		}
		return array('form' => $form->createView());
		
	}

	/**
	 * @Route("/edit/{id}", name="edit_banner")
	 * @Template("CAFAdminBundle:Banner:newBanner.html.twig")
	 */
	public function editBannerAction(Request $request,$id) {
		$banner = $this->getDoctrine()
					 ->getRepository('CAFAdminBundle:Banner')
					 ->find($id);

		$form = $this->createForm(new BannerType(),$banner);
		$path_upload= $this->container->getParameter("path_upload");
		if ($request->getMethod() == 'POST') {
			$old_file = $banner->getFile();
			$form->bindRequest($request);
			if ($form->isValid()) {
				$data = $form->getData();	
				$file= $data->getFile();
				if($file!=""){
					$fileName = $file->getClientOriginalName();
					$file->move($data->getAbsolutePath(), $fileName);
				}
				else{
					$fileName = $old_file;
				}
				$banner->setFile($fileName);
				$banner->setAliasCampaignName($data->getAliasCampaignName());				
				$banner->setAliasBannerName($data->getAliasBannerName());				
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($banner);
				$em->flush();
				$this->get('session')->setFlash('success', 'La bannière a bien été éditée');
				return $this->redirect($this->generateUrl('banner'));
			}
		}
		return array('form' => $form->createView(), 'banner' => $banner, 'path_upload'=>$path_upload);
	}

	/**
	 * @Route("/delete/{id}", name="delete_banner")
	 * @Template()
	 */
	public function deleteBannerAction(Request $request,$id) {
		$banner = $this->getDoctrine()
					 ->getRepository('CAFAdminBundle:Banner')
					 ->find($id);		 
		if (!is_object($banner)) {
			$this->get('session')->setFlash('error', 'La bannière ne peut pas être supprimée');
			return $this->redirect($this->generateUrl('banner'));
		}

		$em = $this->getDoctrine()->getEntityManager();
		$em->remove($banner);
		$em->flush();
		$this->get('session')->setFlash('success', 'La bannière a bien été supprimée');
		return $this->redirect($this->generateUrl('banner'));
	}
	

	/**
	 * @Route("/published/", name="publish_banner")
	 */
	public function pulishFieldAction(Request $request) {
		
		$id    = $request->get('id');
		$state = $request->get('state');

		$em = $this->getDoctrine()->getEntityManager();
		$field = $this->getDoctrine()
					  ->getRepository('CAFAdminBundle:Banner')
					  ->find($id);
		$field->setPublished($state);
		$em->persist($field);
		$em->flush();
		return new Response('');
	}

	 /**
	 * @Route("/action", name="action_banner")
	 * @Template()
	 */
	public function actionBannerAction(Request $request) {
		$listid=$request->request->get('listid');
		$selectaction=$request->request->get('selectaction');
		$erreur=0;
		if(count($listid) && $request->getMethod() == 'POST'){
			$em = $this->getDoctrine()->getEntityManager();
			foreach ($listid as $id) {
				$banner = $this->getDoctrine()
					 ->getRepository('CAFAdminBundle:Banner')
					 ->find($id);
				if (is_object($banner)) {					
					if($selectaction=="delete"){						
						$em->remove($banner);
						$em->flush();
					}
					elseif($selectaction=="unpublish"){
						$banner->setPublished(0);
						$em->persist($banner);
						$em->flush();
					}
					elseif($selectaction=="publish"){
						$banner->setPublished(1);
						$em->persist($banner);
						$em->flush();
					}
				}
			}
			$this->get('session')->setFlash('success', 'L\'action choisie a bien été réalisée');		
		}
		return new Response('');
		
	}

}