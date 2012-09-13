<?php

namespace CAF\CRMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class CaisseRegionaleType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('published', 'publish', array(
				'label' => 'Publié'
				))
				->add('label','text',array('label' => "Label"))		
				->add('numero','text',array('label' => "Numéro"))	
			;
	}

	public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\CRMBundle\Entity\CaisseRegionale');
    }
	
	public function getName() {
		return 'caisseRegionale';
	}
}
