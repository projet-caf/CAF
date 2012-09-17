<?php
namespace CAF\BlocBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use CAF\BlocBundle\Form\ChoiceList\PositionList;

use Doctrine\ORM\EntityRepository;

class BlocType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		//$position = $options['position'];
		$builder
			->add('title')
			->add('display_title', 'choice', array( 
				'choices' => array(
					'1' => 'Oui', 
					'0' => 'Non'
					), 
				'attr' => array(
					'class' => 'display_title publish_field'
					),
				'expanded' => true,
				'multiple' => false,
				'label' => 'Afficher le titre'
				))
			->add('position', 
				'choice', array( 'empty_value' => 'Choose a position', 'choices' => 
					array(
							'top'=>'menu haut', 
							'banner_top'=>'bannière slide', 
							'banner'=>'menu bannière',
							'bottom'=>'menu bas', 
							'top_middle'=>'milieu haut', 
							'bottom_middle'=>'milieu bas', 
							'left'=>'gauche', 
							'right'=>'droite'
					),
				'label' => 'Position'
				))
			->add('published', 'publish', array('label' => 'Publié')
				)
			->add('all_published', 'choice', array( 
				'choices' => array(
					'1' => 'Oui', 
					'0' => 'Non'
					), 
				'attr' => array(
					'class' => 'all_published publish_field'
					),
				'expanded' => true,
				'multiple' => false,
				'label' => 'Toutes les pages'
				))
			->add('categories_fr', 'entity', array(
				'class' => 'CAFContentBundle:CategoryTranslation',
				'query_builder' => 
			    	function(EntityRepository $er) {
				        return $er->getCategoryLang(1);
				    },
				'required' => false,
				'multiple' => true, 
				'label' => 'Catégorie',
				'attr' => array('class' => 'selectable'),
				))
			->add('contents_fr', 'entity', array(
				'class' => 'CAFContentBundle:ContentTranslation',
				'query_builder' => 
			    	function(EntityRepository $er) {
				        return $er->getContentLang(1);
				    },
				'required' => false,
				'multiple' => true,
				'label' => 'Contenu',
				'attr' => array('class' => 'selectable'),
				'property' => 'title',
				))
			->add('categories_en', 'entity', array(
				'class' => 'CAFContentBundle:CategoryTranslation',
				'query_builder' => 
			    	function(EntityRepository $er) {
				        return $er->getCategoryLang(2);
				    },
				'required' => false,
				'multiple' => true, 
				'label' => 'Catégorie',
				'attr' => array('class' => 'selectable'),
				))
			->add('contents_en', 'entity', array(
				'class' => 'CAFContentBundle:ContentTranslation',
				'query_builder' => 
			    	function(EntityRepository $er) {
				        return $er->getContentLang(2);
				    },
				'required' => false,
				'multiple' => true,
				'label' => 'Contenu',
				'attr' => array('class' => 'selectable'),
				'property' => 'title',
				))
			->add('categories_de', 'entity', array(
				'class' => 'CAFContentBundle:CategoryTranslation',
				'query_builder' => 
			    	function(EntityRepository $er) {
				        return $er->getCategoryLang(3);
				    },
				'required' => false,
				'multiple' => true, 
				'label' => 'Catégorie',
				'attr' => array('class' => 'selectable'),
				))
			->add('contents_de', 'entity', array(
				'class' => 'CAFContentBundle:ContentTranslation',
				'query_builder' => 
			    	function(EntityRepository $er) {
				        return $er->getContentLang(3);
				    },
				'required' => false,
				'multiple' => true,
				'label' => 'Contenu',
				'attr' => array('class' => 'selectable'),
				'property' => 'title',
				))
		;
	}

	public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\BlocBundle\Entity\Bloc');
    }

	public function getName() {
		return 'bloc';
	}
}