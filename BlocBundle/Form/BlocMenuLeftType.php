<?php
namespace CAF\BlocBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use CAF\BlocBundle\Form\BlocType;

use Doctrine\ORM\EntityRepository;
use CAF\BlocBundle\Form\ChoiceList\MenuTypeList;

class BlocMenuLeftType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('bloc', new BlocType(), array('label' => ' '))
			->add('menu', 'entity', array(
			    'class' => 'CAFMenuBundle:Menu',
			    'query_builder' => 
			    	function(EntityRepository $er) {
				        return $er
				        		->getTreePublishedByAlias('main-menu');
				    },
				)
			)
		;
	}

	public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\BlocBundle\Entity\BlocMenuLeft');
    }

	public function getName() {
		return 'bloc_menu_left';
	}
}