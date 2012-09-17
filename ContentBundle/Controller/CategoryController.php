<?php
namespace CAF\ContentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CAF\ContentBundle\Entity\Category;
use CAF\ContentBundle\Form\CategoryType;
use CAF\ContentBundle\Loaders\CategoryLoader;

use CAF\ContentBundle\Configuration\ConfigurationTemplate;

use Doctrine\DBAL\DriverManager;

/**
 * @Route("/category")
 */
class CategoryController extends Controller
{

	/**
	 * @Route("/categories/{page}/{nb_elem}", name="categories", defaults={"page"="1", "nb_elem"="20"})
	 * @Template()
	 */
	public function indexAction($page,$nb_elem)
	{
		$categories = $this->getDoctrine()
					  ->getRepository('CAFContentBundle:Category')
					  ->findAllOrder($page, $nb_elem);
		if (!$categories) {
			return array('categories' => null);
		}

		$pagination = $this->getDoctrine()
		        		   ->getRepository('CAFContentBundle:Category')          
		        		   ->getPagination($nb_elem);

		$link = '<div class="pull-right sep">
			<a href="'.$this->generateUrl('new_category').'" class="btn btn-primary">Ajouter</a>
		</div>';     
		return array('categories' => $categories, 'nb_pages' => $pagination, 'page' => $page, 'nb_elem' => $nb_elem, 'link' => $link);	
	}

	/**
	 * @Route("/new", name="new_category")
	 * @Template()
	 **/
	public function newAction()
	{
		//séparateur
		$directory_separator = $this->container->getParameter("directory_separator");

		$category = new Category();
		$repository = $this->getDoctrine()->getRepository('CAFContentBundle:Category');
		$loader = new CategoryLoader($repository);
		$loader->load($category);

		$config = new ConfigurationTemplate();
		$fichiers = $config->getTemplates($directory_separator);

		$form_fr = $this->createForm(new CategoryType(), $category, array('lang' => 'fr', 'canonical' => '', 'fichiers' => $fichiers));
		$form_en = $this->createForm(new CategoryType(), $category, array('lang' => 'en', 'canonical' => '', 'fichiers' => $fichiers));
		$form_de = $this->createForm(new CategoryType(), $category, array('lang' => 'de', 'canonical' => '', 'fichiers' => $fichiers));

		$request = $this->getRequest();

		if ($request->getMethod() == 'POST') {

			$form_fr->bindRequest($request);
			$category_request = $request->get('category');
			$translationFr = $category_request['translationFr'];
			$translationEn = $category_request['translationEn'];
			$translationDe = $category_request['translationDe'];

			//if ($form_fr->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();

				$titles = array();
				$langs = array(1 => 'fr', 2 => 'en', 3 => 'de');
				foreach($langs as $id=>$l) {
					$lang = $this->getDoctrine()
								 ->getRepository('CAFAdminBundle:Language')
								 ->find($id);			 
					$category->{'translation'.ucfirst($l)}->setLang($lang);
					$category->{'translation'.ucfirst($l)}->setCategory($category);
					$category->{'translation'.ucfirst($l)}->setTitle(${'translation'.ucfirst($l)}['title']);
					$category->{'translation'.ucfirst($l)}->setPublished(${'translation'.ucfirst($l)}['published']);
					$category->{'translation'.ucfirst($l)}->setDescription(${'translation'.ucfirst($l)}['description']);
					$category->addCategoryTranslation($category->{'translation'.ucfirst($l)});
				}
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($category);
			    $em->flush();

				$metasvaluesFr = $category_request['metasValuesFr'];
				$metasvaluesEn = $category_request['metasValuesEn'];
				$metasvaluesDe = $category_request['metasValuesDe'];

				$meta_repo = $this->getDoctrine()->getRepository('CAFContentBundle:Metas');
			   
			    $translations = $category->getTranslations();

			    foreach($translations as $t) {
					switch ($t->getLang()->getCode()) {
						case 'fr':
							$t = $loader->loadCategoryTranslation($t, $metasvaluesFr, $meta_repo);
							break;
						case 'en':
							$t = $loader->loadCategoryTranslation($t, $metasvaluesEn, $meta_repo);
							break;
						case 'de':
							$t = $loader->loadCategoryTranslation($t, $metasvaluesDe, $meta_repo);
							break;

					}
					$t->setUrl();		
				}
				$em->persist($category);
			    $em->flush();
				return $this->redirect($this->generateUrl('categories'));
			//}
		}
		return array('form_fr' => $form_fr->createView(), 'form_en' => $form_en->createView(), 'form_de' => $form_de->createView());
	}

	/**
	 * @Route("/edit/{id}", name="edit_category")
	 * @Template("CAFContentBundle:Category:new.html.twig")
	 **/
	public function editAction($id)
	{
		//séparateur
		$directory_separator = $this->container->getParameter("directory_separator");
	
		$category = $this->getDoctrine()
					  ->getRepository('CAFContentBundle:Category')
					  ->find($id);

		$repository = $this->getDoctrine()->getRepository('CAFContentBundle:Category');
		$loader = new CategoryLoader($repository);

		$metasvalue_repo = $this->getDoctrine()->getRepository('CAFContentBundle:MetasValueCategory');  
		$loader->loadCategory($category,$metasvalue_repo);

		$canonicals = $loader->getCanonicals($category->getTranslations());

		$config = new ConfigurationTemplate();
		$fichiers = $config->getTemplates($directory_separator);

		$form_fr = $this->createForm(new CategoryType(), $category, array('lang' => 'fr', 'canonical' => $canonicals['fr'], 'fichiers' => $fichiers));
		$form_en = $this->createForm(new CategoryType(), $category, array('lang' => 'en', 'canonical' => $canonicals['en'], 'fichiers' => $fichiers));
		$form_de = $this->createForm(new CategoryType(), $category, array('lang' => 'de', 'canonical' => $canonicals['de'], 'fichiers' => $fichiers));

		$request = $this->getRequest();

		if ($request->getMethod() == 'POST') {

			$form_fr->bindRequest($request);
			$form_en->bindRequest($request);
			$form_de->bindRequest($request);

			$category_request = $request->get('category');
				
			//if ($form_fr->isValid()) {

				$em = $this->getDoctrine()->getEntityManager();
				
				$translations = $category->getTranslations();

				$metasvaluesFr = $category_request['metasValuesFr'];
				$metasvaluesEn = $category_request['metasValuesEn'];
				$metasvaluesDe = $category_request['metasValuesDe'];
				$meta_repo = $this->getDoctrine()->getRepository('CAFContentBundle:Metas');

				foreach($translations as $t) {
					switch ($t->getLang()->getCode()) {
						case 'fr':
							$t = $loader->updateCategoryTranslation($t, $metasvaluesFr, $meta_repo);
							break;
						case 'en':
							$t = $loader->updateCategoryTranslation($t, $metasvaluesEn, $meta_repo);
							break;
						case 'de':
							$t = $loader->updateCategoryTranslation($t, $metasvaluesDe,  $meta_repo);
							break;
					}
					$t->setUrl();	
				}

			    $em->persist($category);
			    $em->flush();


				return $this->redirect($this->generateUrl('categories'));
			//}
		}
		return array('form_fr' => $form_fr->createView(), 'form_en' => $form_en->createView(), 'form_de' => $form_de->createView(),'id' => $id);
	}


	/**
	 * @Route("/delete/{id}", name="delete_category")
	 * @Template()
	 **/
	public function deleteAction($id)
	{
		$category = $this->getDoctrine()
					  ->getRepository('CAFContentBundle:Category')
					  ->find($id);
		$em = $this->getDoctrine()->getEntityManager();			  
	    $em->remove($category);
	    $em->flush();
		return $this->redirect($this->generateUrl('categories'));
	}

	/**
	 * @Route("/published/", name="publish_category_translation")
	 */
	public function pulishTranslationAction(Request $request) {
		
		$id    = $request->get('id');
		$state = $request->get('state');

		$em = $this->getDoctrine()->getEntityManager();
		$translation = $this->getDoctrine()
					  ->getRepository('CAFContentBundle:CategoryTranslation')
					  ->find($id);
		$translation->setPublished($state);
		$em->persist($translation);
		$em->flush();
		return new Response('');
	}

	/**
	 * @Route("/action", name="category_action")
	 * @Template()
	 */
	public function categoryGroupAction(Request $request)
	{
		$listid=$request->request->get('listid');
		$selectaction=$request->request->get('selectaction');
		$nb_elem = $request->request->get('nb_elem');

		if($selectaction == 'pagination' && $nb_elem > 0) {
			return $this->redirect($this->generateUrl('categories', array('nb_elem' => $nb_elem )));
		}

		$erreur=0;
		if(count($listid) && $request->getMethod() == 'POST'){
			$em = $this->getDoctrine()->getEntityManager();
			foreach ($listid as $id) {
				$translation = $this->getDoctrine()
					 ->getRepository('CAFContentBundle:CategoryTranslation')
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
		return $this->redirect($this->generateUrl('categories'));
	}
}	