<?php

namespace CAF\CRMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class HistoEmailType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('typeEmail', 'entity', array(
	            		'class' => 'CAFCRMBundle:TypeEmail', 
	            		'query_builder' => function(EntityRepository $er) {return $er->createQueryBuilder('te')
	            																		->where('te.published = 1')
	            																		->orderBy('te.type', 'ASC');},
	            		'property' => 'type',
	            		'empty_value' => 'Selectionnez un Type',
	            		'label' => "Type"))
				->add('emailEnvoyeur','email',array('label' => "Email envoyeur",
					'attr' => array('class' => 'emailclass')))		
				->add('emailClient','email',array('label' => "Email client",'attr' => array('class' => 'emailclass')))		
				->add('sujet','text',array('label' => "Sujet"))	
				->add('message','ckeditor')			
				;			
	}

	public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\CRMBundle\Entity\HistoEmail');
    }
	
	public function getName() {
		return 'HistoEmail';
	}
}
