<?php

namespace CAF\CRMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;



class ConseillerType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('published', 'publish', array(
				'label' => 'Publié'
				))
				->add('nom','text',array('label' => "Nom"))		
				->add('prenom','text',array('label' => "Prénom"))	
				->add('email','email',array('label' => "Email"))	
				->add('numtel','text',array('label' => "Numéro de téléphone"))	
				->add('agence', 'entity', array(
	            		'class' => 'CAFCRMBundle:Agence', 
	            		'query_builder' => function(EntityRepository $er) {return $er->createQueryBuilder('agence')->orderBy('agence.nom', 'ASC');},
	            		'property' => 'nom'));
	}

	public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\CRMBundle\Entity\Conseiller');
    }
	
	public function getName() {
		return 'conseiller';
	}
}
