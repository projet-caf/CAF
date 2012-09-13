<?php

namespace CAF\FormBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;


class FormTaxonomyPopupType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{

		$builder->add('id_form_taxonomy', 'entity', array(
					'class'       => 'CAFFormBundle:FormTaxonomy',
					'empty_value' => 'Choisissez un type de formulaire',
					'property' 	  => 'libelle',
					'label'		  => 'Type de formulaire'
					)
				)
		;
	}

	public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\FormBundle\Entity\Formulaire');
    }
	
	public function getName() {
		return 'form_taxonomy_popup';
	}
}

?>