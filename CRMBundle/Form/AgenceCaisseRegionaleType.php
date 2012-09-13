<?php

namespace CAF\CRMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class AgenceCaisseRegionaleType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('published', 'publish', array(
				'label' => 'Publié'
				))
				->add('ville','text',array('label' => "Ville"))		
				->add('numero','text',array('label' => "Numéro"))	
				->add('caisseRegional', 'entity', array(
	            		'class' => 'CAFCRMBundle:CaisseRegionale', 
	            		'query_builder' => function(EntityRepository $er) {return $er->createQueryBuilder('caisseRegional')->orderBy('caisseRegional.label', 'ASC');},
	            		'property' => 'numlabel',
	            		'label' => "Caisse régionale rattachée"));
			;
	}

	public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\CRMBundle\Entity\AgenceCaisseRegionale');
    }
	
	public function getName() {
		return 'agenceCaisseRegionale';
	}
}
