<?php

namespace CAF\CRMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class AgenceType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('published', 'publish', array(
				'label' => 'Publié'
				))
				->add('nom','text',array('label' => "Nom"))		
			;
	}

	public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\CRMBundle\Entity\Agence');
    }
	
	public function getName() {
		return 'agence';
	}
}
