<?php

namespace CAF\FormBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use CAF\FormBundle\Form\Type\TemplateType;


class FormTaxonomyType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{

		$builder->add('libelle')
				->add('published', 'publish', array('label' => 'Publié'))
				->add('formfields','entity',array(
					'class' => 'CAFFormBundle:FormFields',
					'multiple' => true,
					'required' => false,
					'by_reference' => false
					));
		
	}

    public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\FormBundle\Entity\FormTaxonomy');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'CAF\FormBundle\Entity\FormTaxonomy'));
    }
	
	public function getName() {
		return 'form_taxonomy';
	}
}

?>