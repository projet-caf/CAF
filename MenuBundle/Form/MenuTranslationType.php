<?php

namespace CAF\MenuBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class MenuTranslationType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{

		$lang_id = $options['lang_id'];
		$menu_taxonomy = $options['menu_taxonomy'];
		$builder->add('title','text', array('label' => 'Titre'))
				->add('slug', 'text', array('required' => false, 'label' => 'Alias'))
				->add('published', 'publish')
				->add('countries', 'entity', array(
					'class' => 'CAFAdminBundle:Country',
					'multiple' => true,
					'property' => 'name',
					'required' => false,
					'label' => 'Pays',
				))

				->add('link_taxonomy', 'link', array('attr' => array('class' => 'link'), 'label' => 'Type de lien'))
				->add('category','entity', array(
					'attr' => array('class' => 'category'),
					'class' => 'CAFContentBundle:CategoryTranslation',
					'query_builder' => function(EntityRepository $er) use ($lang_id) {
						return $er->getCategoryLang($lang_id);
					},
					'empty_value' => 'Choisissez une catégorie',
					'required' => false,
					))
				->add('content','entity', array(
					'attr' => array('class' => 'category'),
					'class' => 'CAFContentBundle:ContentTranslation',
					'empty_value' => 'Choisissez un contenu',
					'query_builder' => function(EntityRepository $er) use ($lang_id) {
						return $er->getContentLang($lang_id);
					},
					'required' => false,
					))
				->add('urls', 'text', array('attr' => array('class' => 'url'),'required' => false))
				->add('parent', 'entity', array(
				    'class' => 'CAFMenuBundle:Menu',
				    'query_builder' => function(EntityRepository $er) use ($menu_taxonomy) {
				        return $er->getMenuParent($menu_taxonomy);
				    },
					'empty_value' => 'Choisissez un menu parent',
					'required' => false,
				))
				->add('media', 'image')
				->add('id_menu_taxonomy', 'entity', array(
					    'class' => 'CAFMenuBundle:MenuTaxonomy',
						'empty_value' => false,
						'preferred_choices' => array($menu_taxonomy),
						'property' => 'name',
						'read_only' => true,
						'label' => ' '
					)
				)
		;	
	}

	public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\MenuBundle\Entity\Menu', 'menu_taxonomy' => 1, 'lang_id' => 2);
    }

	public function getName() {
		return 'menu_translation';
	}
}

