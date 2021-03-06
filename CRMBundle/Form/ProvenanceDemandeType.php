<?php

namespace CAF\CRMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class ProvenanceDemandeType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('published', 'publish', array(
				'label' => 'Publié'
				))
				->add('libelle','text',array('label' => "Provenance"))		
			;
	}

	public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\CRMBundle\Entity\ProvenanceDemande');
    }
	
	public function getName() {
		return 'provenanceDemande';
	}
}
