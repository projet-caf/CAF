<?php

namespace CAF\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;


class MetasType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{

		$builder
			->add('name')
			->add('type','choice', array(
			'choices' => array('normal' => 'normal', 'og' => 'open graph', 'other' => 'Autre')
			))
			->add('display', 'choice', array('label' => 'Affichage', 'choices' => array('text' => 'text', 'textarea' => 'textarea')))
			->add('balise','textarea');	
	}

	public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\ContentBundle\Entity\Metas', 'lang' => 'fr');
    }
	
	public function getName() {
		return 'metas';
	}
}

?>