<?php
namespace CAF\BlocBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use CAF\BlocBundle\Form\BlocType;

use Doctrine\ORM\EntityRepository;
use CAF\BlocBundle\Form\ChoiceList\MenuTypeList;

class BlocMenuType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('bloc', new BlocType(), array('label' => ' '))
			->add('menu', 'entity', array(
			    'class' => 'CAFMenuBundle:MenuTaxonomy',
			    'query_builder' => 
			    	function(EntityRepository $er) {
				        return $er->createQueryBuilder('m')->orderBy('m.name', 'ASC');
				    },
				'property' => 'name'
					)
				)
			->add('display_type', 
				'choice', array( 'empty_value' => 'Choose a display format','choices' =>  array('banner'=>'bannière', 'header'=>'menu haut', 'footer'=>'menu bas'))
				)
		;
	}

	public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\BlocBundle\Entity\BlocMenu');
    }

	public function getName() {
		return 'bloc_menu';
	}
}