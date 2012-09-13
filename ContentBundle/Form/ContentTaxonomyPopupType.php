<?php

namespace CAF\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;


class ContentTaxonomyPopupType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{

		$builder->add('id_content_taxonomy', 'entity', array(
					'class'       => 'CAFContentBundle:ContentTaxonomy',
					'empty_value' => 'Choisissez un type de contenu',
					'property' 	  => 'libelle',
					'label'		  => 'Type de contenu'
					)
				)
		;
	}

	public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\ContentBundle\Entity\Content');
    }
	
	public function getName() {
		return 'content_taxonomy_popup';
	}
}

?>