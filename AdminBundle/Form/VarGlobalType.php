<?php

namespace CAF\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class VarGlobalType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('published', 'publish', array(
				'label' => 'Publié'
				))
				->add('libelle','text',array('label' => "Libellé"))
				->add('value', 'text', array('label' => 'Valeur'))		
				->add('tag','text', array('label' => 'Tag'))			
			;
	}

	public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\AdminBundle\Entity\VarGlobal');
    }
	
	public function getName() {
		return 'varGlobal';
	}
}
