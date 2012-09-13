<?php

namespace CAF\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use CAF\ContentBundle\Form\Type\TemplateType;


class ContentTaxonomyType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{

		$builder->add('libelle')
				->add('published', 'publish', array('label' => 'Publié'))
				->add('fields','entity',array(
					'class' => 'CAFContentBundle:Fields',
					'multiple' => true,
					'required' => false,
					'by_reference' => false
					))
				->add('template','choice', array('choices' => $options['fichiers']));
		;
	}

    public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\ContentBundle\Entity\ContentTaxonomy', 'fichiers' => array());
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'CAF\ContentBundle\Entity\ContentTaxonomy', 'fichiers' => ''));
    }
	
	public function getName() {
		return 'content_taxonomy';
	}
}

?>