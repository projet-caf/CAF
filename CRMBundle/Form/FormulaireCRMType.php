<?php

namespace CAF\CRMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class FormulaireCRMType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('currentStatut',  new HistoStatutType)
				->add('currentTypeEmail',  new HistoEmailType);
			
	}

	public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\CRMBundle\Entity\FormulaireCRM');
    }
	
	public function getName() {
		return 'formulaireCRM';
	}
}
