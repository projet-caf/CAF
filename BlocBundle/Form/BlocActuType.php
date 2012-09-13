<?php
namespace CAF\BlocBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use CAF\BlocBundle\Form\BlocType;

use Doctrine\ORM\EntityRepository;
use CAF\BlocBundle\Form\ChoiceList\MenuTypeList;

class BlocActuType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('bloc', new BlocType)
			->add('category', 'entity', array(
					    'class' => 'CAFContentBundle:Category',
					)
				)
			->add('limit_value')
			->add('display_type', 
				'choice', array( 'empty_value' => 'Choose a display format','choices' =>  array('document'=>'Documents', 'actu'=>'Actualités'))
				)
		;
	}

	public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\BlocBundle\Entity\BlocActu');
    }

	public function getName() {
		return 'bloc_actu';
	}
}