<?php

namespace CAF\MenuBundle\Controller;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CAF\MenuBundle\Entity\MenuTaxonomy;
use CAF\MenuBundle\Entity\Menu;
use CAF\MenuBundle\Entity\MenuTranslation;

use CAF\MenuBundle\Form\MenuTaxonomyType;
use CAF\MenuBundle\Form\MenuType;
use CAF\MenuBundle\Form\MenuTranslationType;

use CAF\AdminBundle\Form\TranslationType;
use CAF\AdminBundle\Loaders\MenuLoader;

use Doctrine\ORM\Query;
use Gedmo\Translatable\TranslatableListener;


class MenuController extends Controller
{

	/**
	 * @Route("/", name="menus_taxonomy")
	 * @Template()
	 **/
	public function indexAction() {
		$menus_taxonomy = $this->getDoctrine()
		        ->getRepository('CAFMenuBundle:MenuTaxonomy')
		        ->findAll();

		if (!$menus_taxonomy) {
        	return array('menus' => null);
    	}
		return array('menus' => $menus_taxonomy);
	}

	/**
	 * @Route("/new", name="new_menu_taxonomy")
	 * @Template("")
	 **/
	public function newAction(Request $request) {
		$menu_taxonomy = new MenuTaxonomy();
		$form = $this->createForm(new MenuTaxonomyType(), $menu_taxonomy);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$data = $form->getData();	
				$em = $this->getDoctrine()->getEntityManager();
			    $em->persist($menu_taxonomy);
			    $em->flush();
			    $this->get('session')->setFlash('success', 'New menu were saved!');
				return $this->redirect($this->generateUrl('menus_taxonomy'));
			}
		}
		return array('form' => $form->createView(), 'id' => 0);
	}

	/**
	 * @Route("/edit/{id}", name="edit_menu_taxonomy")
	 * @Template("")
	 **/
	public function editAction(Request $request,$id) {
		$menu_taxonomy = $this->getDoctrine()
		        ->getRepository('CAFMenuBundle:MenuTaxonomy')
		        ->find($id);
		$form = $this->createForm(new MenuTaxonomyType(), $menu_taxonomy);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$data = $form->getData();	
				$em = $this->getDoctrine()->getEntityManager();
			    $menu_taxonomy->setName($data->getName());
			    $menu_taxonomy->setAlias($data->getAlias());
			    $em->flush();
			     $this->get('session')->setFlash('success', 'New menu edited!');
				return $this->redirect($this->generateUrl('menus_taxonomy'));
			}
		}
		return $this->render('CAFMenuBundle:Menu:new.html.twig', array('form' => $form->createView(), 'id' => $id));
	}


	/**
	 * @Route("/entries/{menu}/{nb_elem}/{page}", name="entries", defaults={"page" = "1", "nb_elem" = "10"})
	 * @Template()
	 **/
	public function indexEntriesAction($menu,$page,$nb_elem) {
		$em = $this->getDoctrine()->getEntityManager();
		$repository = $em->getRepository('CAFMenuBundle:Menu');
		$query = $repository->getTreePagination($menu,$page,$nb_elem);

		$pagination = $this->getDoctrine()
		        		   ->getRepository('CAFMenuBundle:Menu')          
		        		   ->getPagination($menu, $nb_elem);

		// set hint to translate nodes
		$query->setHint(
		    Query::HINT_CUSTOM_OUTPUT_WALKER,
		    'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
		);

                $translation_exists = $this->getDoctrine()
                                            ->getRepository('CAFMenuBundle:Menu')   
                                            ->getTranslationMenu();

                
                $dataTranslationExists = array();
                foreach($translation_exists as $translation){                    
                    $dataTranslationExists[$translation["object"]["id"]][$translation["locale"]] = "1"; 
                }
                
                
		$entries_fr = $query->getArrayResult();

                $query->setHint(TranslatableListener::HINT_TRANSLATABLE_LOCALE, 'en_us');
		$entries_en = $query->getArrayResult();
		$query->setHint(TranslatableListener::HINT_TRANSLATABLE_LOCALE, 'de_de');
		$entries_de = $query->getArrayResult();

		$link = '<div class="pull-right sep">
			<a href="'.$this->generateUrl('new_entry',array('menu_taxonomy' => $menu, 'nb_elem' => $nb_elem)).'" class="btn btn-primary">Ajouter</a>
		</div>';        		   
		return array('entries_fr' => $entries_fr, 'entries_en' => $entries_en, 'entries_de' => $entries_de, 'menu' => $menu, 'nb_pages' => $pagination, 'page' => $page, 'nb_elem' => $nb_elem, 'link' => $link, 'dataTranslationExists' => $dataTranslationExists);
	}

	/**
	 * @Route("/entry/new/{menu_taxonomy}/{nb_elem}", name="new_entry", defaults={"nb_elem"=10})
	 * @Template()
	 **/
	public function newEntryAction($menu_taxonomy,$nb_elem) {
		
		$em = $this->getDoctrine()->getEntityManager();

		$menu = new Menu();
		$menu_taxonomy_obj = $this->getDoctrine()->getRepository('CAFMenuBundle:MenuTaxonomy')->find($menu_taxonomy);
              
		$form = $this->createForm(new MenuType(), $menu, array('menu_taxonomy' => $menu_taxonomy_obj));

		$request = $this->getRequest();

		if ('POST' === $request->getMethod()) {

			$form->bindRequest($request);

			if ($form->isValid()) {

				$menu_translation = $request->request->get('menu');
				$category = $menu_translation['category'];
				$content = $menu_translation['content'];
				if($content == '')
					$content = 0;
				
				$menu->setCategory($category);
				$menu->setContent($content);

				if ($menu->getOrdre() != null) {
					$after = $menu->getOrdre();
					if ($after->getId() != $after->getRoot()) {
						$repo = $em->getRepository('CAFMenuBundle:Menu');
						$repo->persistAsNextSiblingOf($menu, $after);
					} else {
						$em->persist($menu);
					}
					
				} else {
					$em->persist($menu);
                	
				}
				$em->flush();	
                $this->get('session')->setFlash('success', 'Nouvelle entrée sauvegardée');

				return $this->redirect($this->generateUrl('entries',array('menu' => $menu_taxonomy, 'nb_elem' => $nb_elem)));
			}
		}
		return array('form' => $form->createView(), 'ref_id' => 0, 'lang' => '', 'id' => 0, 'target' => null, 'menu_taxonomy' => $menu_taxonomy, 'nb_elem' => $nb_elem);
	}

	/**
	 * @Route("/entry/translation/new/{menu_taxonomy}/{id}/{lang}/{nb_elem}", name="new_translation", defaults={"menu_taxonomy"=1, "id"=1, "lang"="en_en", "nb_elem"=10})
	 * @Template()
	 */
	public function newTranslationAction($menu_taxonomy,$id,$lang,$nb_elem)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$menu = new Menu();
		$menu_taxonomy_obj = $this->getDoctrine()->getRepository('CAFMenuBundle:MenuTaxonomy')->find($menu_taxonomy);
		$locales = $this->container->getParameter('locale_lang');
		$locales = array_flip($locales);
		$lang_id = $this->getDoctrine()
						->getRepository('CAFAdminBundle:Language')
						->findBy(array('code'=>$locales[$lang]));

		$lang_id = current($lang_id);
		$form = $this->createForm(new MenuTranslationType(), $menu, array('menu_taxonomy' => $menu_taxonomy_obj, 'lang_id' => $lang_id->getId()));

		$request = $this->getRequest();

		if ('POST' === $request->getMethod()) {

			$form->bindRequest($request);

			if ($form->isValid()) {

				$data = $form->getData();
				$menu_translation = $request->request->get('menu_translation');
				$category = $menu_translation['category'];
				$content = $menu_translation['content'];

				$canonicalCategory = '';
				$canonicalContent = '';
				$urlsContent = '';

				if ($category != null) {
					$categoryTranslation = $this->getDoctrine()
												->getRepository('CAFContentBundle:CategoryTranslation')
												->find($category);
					$urlsContent = $this->getDoctrine()
										->getRepository('CAFContentBundle:CategoryTranslation')
										-> getAbsoluteUrl($categoryTranslation->getId());

					//var_dump($urlsContent); die();

					if(!empty($urlsContent)) {
						$urlsContent = $urlsContent[0]['value'];
						$urlsContent = unserialize($urlsContent);
					} else
						$urlsContent = '';

					if($urlsContent == '') {
						$canonicalCategory = $this->getDoctrine()
									  ->getRepository('CAFContentBundle:CategoryTranslation')
									  ->getCanonical($categoryTranslation->getId());
						 if(!empty($canonicalCategory))	{
						 	$canonicalCategory = $canonicalCategory[0]['value'];
						 	$canonicalCategory = unserialize($canonicalCategory).'.html';
						 }
							

					}																  

				}

				if ($content != null) {
					$contentTranslation = $this->getDoctrine()
												->getRepository('CAFContentBundle:ContentTranslation')
												->find($content);								
					$urlsContent = $this->getDoctrine()
									  ->getRepository('CAFContentBundle:ContentTranslation')
									  ->getAbsoluteUrl($contentTranslation->getId());
					$urlsContent = $urlsContent[0]['url'];
					if($urlsContent == '') {			  
						$canonicalContent = $this->getDoctrine()
									  			 ->getRepository('CAFContentBundle:CategoryTranslation')
									  			 ->getCanonical($contentTranslation->getId());
					}				  

				}

				$em = $this->getDoctrine()->getEntityManager();
				$repository = $em->getRepository('CAFMenuBundle:Menu');

				$original = $repository->find($id);
				$original->setTranslatableLocale($lang);                               
                $original->setUrlsContent($urlsContent,$canonicalContent,$canonicalCategory);           
				$original->setTitle($menu->getTitle());
				$original->setCountries($menu->getCountries());
				$original->setLinkTaxonomy($menu->getLinkTaxonomy());
				$original->setPublished($menu->getPublished());
				$original->setUrls($menu->getUrls());
				$original->setCategory($category);
				$original->setContent($content);
				$original->setMedia($menu->getMedia());
				
				
				$em->persist($original);
				$em->flush();


                $this->get('session')->setFlash('success', 'Nouvelle entrée sauvegardée');

				return $this->redirect($this->generateUrl('entries',array('menu' => $menu_taxonomy, 'nb_elem' => $nb_elem)));
			}
		}
		return array('form' => $form->createView(), 'ref_id' => 0, 'lang' => $lang, 'id' => $id, 'target' => null, 'menu_taxonomy' => $menu_taxonomy, 'nb_elem' => $nb_elem);
	}

	/**
	 * @Route("/entry/translation/edit/{menu_taxonomy}/{id}/{lang}/{nb_elem}", name="edit_translation", defaults={"menu_taxonomy"=1, "id"=1, "lang"="en_en", "nb_elem"=10})
	 * @Template("CAFMenuBundle:Menu:newTranslation.html.twig")
	 */
	public function editTranslationAction($menu_taxonomy,$id,$lang, $nb_elem)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$menu = $this->getDoctrine()->getRepository('CAFMenuBundle:Menu')->find($id);
		$menu->setTranslatableLocale($lang);
		$em->refresh($menu);

		$menu_taxonomy_obj = $this->getDoctrine()->getRepository('CAFMenuBundle:MenuTaxonomy')->find($menu_taxonomy);
		$locales = $this->container->getParameter('locale_lang');
		$locales = array_flip($locales);
		$lang_id = $this->getDoctrine()
						->getRepository('CAFAdminBundle:Language')
						->findBy(array('code'=>$locales[$lang]));

		$lang_id = current($lang_id);
		$form = $this->createForm(new MenuTranslationType(), $menu, array('menu_taxonomy' => $menu_taxonomy_obj, 'lang_id' => $lang_id->getId()));

		$request = $this->getRequest();

		if ('POST' === $request->getMethod()) {

			$form->bindRequest($request);

			if ($form->isValid()) {
 
                $data = $form->getData();
				$menu_translation = $request->request->get('menu_translation');
				$category = $menu_translation['category'];
				$content = $menu_translation['content'];

				$canonicalCategory = '';
				$canonicalContent = '';
				$urlsContent = '';

				if ($category != null) {
					$categoryTranslation = $this->getDoctrine()
												->getRepository('CAFContentBundle:CategoryTranslation')
												->find($category);
					$urlsContent = $this->getDoctrine()
										->getRepository('CAFContentBundle:CategoryTranslation')
										-> getAbsoluteUrl($categoryTranslation->getId());

					//var_dump($urlsContent); die();

					if(!empty($urlsContent)) {
						$urlsContent = $urlsContent[0]['value'];
						$urlsContent = unserialize($urlsContent);
					} else
						$urlsContent = '';

					if($urlsContent == '') {
						$canonicalCategory = $this->getDoctrine()
									  ->getRepository('CAFContentBundle:CategoryTranslation')
									  ->getCanonical($categoryTranslation->getId());
						 if(!empty($canonicalCategory))	{
						 	$canonicalCategory = $canonicalCategory[0]['value'];
						 	$canonicalCategory = unserialize($canonicalCategory).'.html';
						 }
							

					}																  

				}

				if ($content != null) {
					$contentTranslation = $this->getDoctrine()
												->getRepository('CAFContentBundle:ContentTranslation')
												->find($content);								
					$urlsContent = $this->getDoctrine()
									  ->getRepository('CAFContentBundle:ContentTranslation')
									  ->getAbsoluteUrl($contentTranslation->getId());
					$urlsContent = $urlsContent[0]['url'];
					if($urlsContent == '') {			  
						$canonicalContent = $this->getDoctrine()
									  			 ->getRepository('CAFContentBundle:CategoryTranslation')
									  			 ->getCanonical($contentTranslation->getId());
					}				  

				}

				$em = $this->getDoctrine()->getEntityManager();
				$repository = $em->getRepository('CAFMenuBundle:Menu');

				$original = $repository->find($id);
				$original->setTranslatableLocale($lang);                               
                $original->setUrlsContent($urlsContent,$canonicalContent,$canonicalCategory);           
				$original->setTitle($menu->getTitle());
				//$original->setCountries($menu->getCountries());
				$original->setLinkTaxonomy($menu->getLinkTaxonomy());
				$original->setPublished($menu->getPublished());
				$original->setUrls($menu->getUrls());
				$original->setCategory($category);
				$original->setContent($content);
				$original->setMedia($menu->getMedia());
				
				
				$em->persist($original);
				$em->flush();




                $this->get('session')->setFlash('success', 'Nouvelle entrée sauvegardée');

				return $this->redirect($this->generateUrl('entries',array('menu' => $menu_taxonomy,'lang' => $lang)));
			}
		}
		return array('form' => $form->createView(), 'ref_id' => 0, 'lang' => $lang, 'id' => $id, 'target' => 'menu', 'menu_taxonomy' => $menu_taxonomy, 'nb_elem' => $nb_elem);
	}

	/**
	 * @Route("/entry/edit/{menu_taxonomy}/{id}/{nb_elem}", name="edit_entry", defaults={"menu_taxonomy"=1, "id"=1, "nb_elem"=10})
	 * @Template("CAFMenuBundle:Menu:newEntry.html.twig")
	 **/
	public function editEntryAction(Request $request,$id,$menu_taxonomy, $nb_elem)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$menu = $this->getDoctrine()->getRepository('CAFMenuBundle:Menu')->find($id);
		$menu_taxonomy_obj = $this->getDoctrine()->getRepository('CAFMenuBundle:MenuTaxonomy')->find($menu_taxonomy);
		$form = $this->createForm(new MenuType(), $menu, array('menu_taxonomy' => $menu_taxonomy_obj));

		if ('POST' === $request->getMethod()) {

			$form->bindRequest($request);

			if ($form->isValid()) {

				$menu_translation = $request->request->get('menu');
				$category = $menu_translation['category'];
				$content = $menu_translation['content'];
				
				$menu->setCategory($category);
				$menu->setContent($content);

                if ($menu->getOrdre() != null) {
					$after = $menu->getOrdre();
					if ($after->getId() != $after->getRoot()) {
						$repo = $em->getRepository('CAFMenuBundle:Menu');
						$repo->persistAsNextSiblingOf($menu, $after);
					} else {
						$em->persist($menu);
					}
					
				} else {
					$em->persist($menu);
                	
				}
				$em->flush();

                $this->get('session')->setFlash('success', 'Entrée sauvegardée');

				return $this->redirect($this->generateUrl('entries',array('menu' => $menu->getIdMenuTaxonomy()->getId(), 'nb_elem' => $nb_elem)));
			}
		}
		return array('form' => $form->createView(), 'ref_id' => 0, 'lang' => '', 'id' => $id, 'menu_taxonomy' => $menu_taxonomy, 'target' => 'menu', 'nb_elem' => $nb_elem);
	}

	/**
	 * Change l'ordre des éléments dans les menus
	 * @Route("/entries/ordre/{menu_taxonomy}/{entry}/{sens}/{nb_elem}", name="changeOrdreMenu")
	 * @Template()
	 */
	public function changeOrdreAction($menu_taxonomy,$entry,$sens,$nb_elem)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$repo = $em->getRepository('CAFMenuBundle:Menu');
		$entryObj = $repo->find($entry);

		if($sens == 'UP')
			$repo->moveUp($entryObj,1);
		else
			$repo->moveDown($entryObj,1);
		
		return $this->redirect($this->generateUrl('entries', array('menu' => $menu_taxonomy, 'nb_elem' => $nb_elem)));
	}


	/**
	 * @Route("/entry/delete/{id}/{menu_query}", name="delete_entry")
	 * @Template()
	 */
	public function deleteEntryAction($id,$menu_query,Request $request) {
		$em = $this->getDoctrine()->getEntityManager();
		$repo = $em->getRepository('CAFMenuBundle:Menu');
		$menu = $repo->find($id);
		$children = $menu->getChildren();
		if (!$menu) {
			throw $this->createNotFoundException('No menu found for id '.$id);
		}

		if(count($children) == 0) {
			$parent = $menu->getParent();
			$repo->removeFromTree($menu);
			$em->clear(); // clear cached nodes
			$em->flush();
		} else {
			 $this->get('session')->setFlash('error', 'Ce menu contient des sous-menus. Vous devez d\'abord supprimer ces sous-menus');
		}

		return $this->redirect($this->generateUrl('entries', array('menu' => $menu_query)));
	}

	/**
	 * @Route("/entry/getchildren", name="get_children")
	 * @Template("CAFMenuBundle:Menu:liste.html.twig")
	 */

	public function getChildrenAction(Request $request)
	{               

	    if($request->isXmlHttpRequest())
	    {
	        $parent = '';
	        $parent = $request->request->get('parent');

	        $em = $this->container->get('doctrine')->getEntityManager();

	        if($parent != '')
	        {
	               $qb = $em->createQueryBuilder();

	               $qb->select('m,t')
	                 ->from('CAFMenuBundle:Menu','m')
                        ->leftjoin('m.translations', 't')
	                 ->where("m.parent = :parent")
	                 ->orderBy('m.lft', 'ASC')
	                 ->setParameter('parent', $parent);

	               $query = $qb->getQuery();               
	               $children = $query->getResult();
	        }
	        else {
	            $children = $em->getRepository('CAFMenuBundle:Menu')->findAll();
	        }

	        return $this->container->get('templating')->renderResponse('CAFMenuBundle:Menu:liste.html.twig', array(
	            'children' => $children
	            ));
	    }
	   	return $this->newEntryAction();
	}

	/**
	 * @Template("::menu.html.twig")
	 * @Cache(smaxage=3600)
	 */
	public function getMenuAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$repository = $em->getRepository('CAFMenuBundle:Menu');
		$query = $repository->getTree(1);
		$query->setHint(
		    Query::HINT_CUSTOM_OUTPUT_WALKER,
		    'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
		);
		$nodes = $query->getArrayResult();
		$options = array('decorate' => false);
		$entries = $repository->buildTree($nodes, $options);
		return array('entries' => $entries);
	}

	/**
	 * @Route("/published/", name="publish_menutranslation")
	 */
	public function publishMenuTranslationAction(Request $request) {
		
		$id    = $request->get('id');
		$state = $request->get('state');

		$em = $this->getDoctrine()->getEntityManager();
		$translation = $this->getDoctrine()
					  ->getRepository('CAFMenuBundle:Menu')
					  ->find($id);
		$translation->setPublished($state);
		$em->persist($translation);
		$em->flush();
		return new Response('');
	}

	/**
	 * @Route("/action/{menu}/{nb_elem}", name="action_menu")
	 * @Template()
	 */
	public function menuGroupAction(Request $request, $menu, $nb_elem) {
		$listid=$request->request->get('listid');
		$selectaction=$request->request->get('selectaction');
		$nb_elem = $request->request->get('nb_elem');
		if($selectaction == 'pagination' && $nb_elem > 0) {
			return $this->redirect($this->generateUrl('entries', array('menu' => $menu,'page' => 1, 'nb_elem' => $nb_elem )));
		}

		$erreur=0;
		if(count($listid) && $request->getMethod() == 'POST'){
			$em = $this->getDoctrine()->getEntityManager();
			foreach ($listid as $id) {
				$translation = $this->getDoctrine()
					 ->getRepository('CAFMenuBundle:Menu')
					 ->find($id);
				if (is_object($translation)) {
					switch($selectaction) {
						case 'delete':						
							$em->remove($translation);
							$em->flush();
							break;
						case "unpublish":
							$translation->setPublished(0);
							$em->persist($translation);
							$em->flush();
							break;
						case "publish":
							$translation->setPublished(1);
							$em->persist($translation);
							$em->flush();
							break;
					}				
					
				}
			}
			$this->get('session')->setFlash('success', 'L\'action choisie a bien été réalisée');		
		}
		return $this->redirect($this->generateUrl('entries', array('menu' => $menu, 'nb_elem' => $nb_elem)));
		
	}

        /**
	 * @Route("/getCategoryAjax", name="getCategoryAjax")
	 * @Template()
	 **/
	public function getAllCategoryAction(Request $request)
	{
            if($request->isXmlHttpRequest())
	    {
	        $idcat = $request->request->get('idcat');

	        $em = $this->container->get('doctrine')->getEntityManager();

	        if($idcat)
	        {
                      $message = $this->getDoctrine()
					 ->getRepository('CAFContentBundle:CategoryTranslation')
					 ->find($idcat);	
                       $contents = $message->getContents();
                       
                       return $this->container->get('templating')->renderResponse('CAFAdminBundle:Select:select.html.twig', array(
		            'message' =>  $contents
		            ));
                         
                        
	        }     
	       
	    }
	   	return null;            
	}
	

	


}