<?php

namespace CAF\FormBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;


class FormFieldsType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{

		$builder->add('libelle')
				->add('name')
				->add('published', 'publish', array('label' => 'Publié'))
				->add('required', 'choice', array(
					'label' => 'Obligatoire',
					'choices' => array(1 => 'Oui', 0 => 'Non'),
					'attr' => array('class' => 'publish_field'),
					'expanded' => true
					))
				->add('id_form_field_taxonomy', 'entity', array(
					'label' => 'Type de champ',
				    'class' => 'CAFFormBundle:FormFieldTaxonomy',
					'empty_value' => 'Choisissez un type de champ',
					'property' => 'name',
					)
				)
				->add('form_taxonomies', 'entity', array(
					'label' => 'Type de contenu',
					'class' => 'CAFFormBundle:FormTaxonomy',
					'multiple' => true,
					'property' => 'libelle',
					)
				)
				->add('ordre', 'entity', array(
					'class' => 'CAFFormBundle:FormFields',
					'property' => 'libelle',
					'empty_value' => 'Après le champ',
					'required' => false
					)
				)
			;
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'CAF\FormBundle\Entity\FormFields'));
    }
	
	public function getName() {
		return 'form_taxonomy';
	}
}