<?php

namespace CAF\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class BannerType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		//$choices=new PositionList();
		$builder->add('published', 'publish', array(
				'label' => 'Publié',
				))
				->add('banner_name','text',array('label' => "Nom de la bannière"))
				->add('campaign_name','text',array('label' => "Nom de la campagne"))
				->add('file','file',array(
					'label' => "Image à afficher",
					'required' => false))
				->add('link','url',array('label' => "Lien d'atterrissage de la bannière"))		
			;
	}

	public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\AdminBundle\Entity\Banner');
    }
	
	public function getName() {
		return 'banner';
	}
}
