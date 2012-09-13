<?php

namespace CAF\BlocBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use CAF\BlocBundle\Form\ChoiceList\BlocLabelList;


class BlocLabelPopupType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{

		$builder->add('type', 'choice', array(
					'choices'       =>array(
						'BlocMenu'=>'module menu', 
						'BlocActu'=>'module categorie', 
						'BlocHtml' =>'module html',
						'BlocMenuLeft' =>'module menu latéral',
						'BlocBanner' =>'module bannière',
						'BlocBannerSlide' =>'module bannière (slider)',
						'BlocBannerRight' =>'module bannière droit',
					),
					'empty_value' => 'Choisissez un type de bloc',
					)
				)
		;
	}

	public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\BlocBundle\Entity\Bloc');
    }
	
	public function getName() {
		return 'bloc_popup';
	}
}

?>