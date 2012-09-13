<?php

namespace CAF\FormBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

use CAF\FormBundle\Entity\FieldsRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FormulaireType extends AbstractType
{


	public function buildForm(FormBuilderInterface $builder, array $options)
	{	
		$form_taxonomy = $options['form_taxonomy'];
		$builder
			->add('title','text',array('label' => 'Titre', 'required' => false))
			->add('values', 'valuesform', array('form_taxonomy' => $options['form_taxonomy'], 'label' => ' '))
			->add('published', 'publish', array('label' => 'Publié'))
			;
	}

   public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\FormBundle\Entity\Formulaire', 'form_taxonomy' => 1);
    }
		

	public function getName() {
		return 'formulaire';
	}
}

?>