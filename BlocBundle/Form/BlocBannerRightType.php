<?php
namespace CAF\BlocBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use CAF\BlocBundle\Form\BlocType;

use Doctrine\ORM\EntityRepository;
use CAF\BlocBundle\Form\ChoiceList\MenuTypeList;

class BlocBannerRightType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('bloc', new BlocType(), array('label' => ' '))			
			->add('date_debut','date',array(
				'label' =>  "Date de début d'affichage",
				'widget' => 'single_text',
				'input' => 'datetime',
				'format' => 'dd/MM/yyyy',
				'attr' => array('class' => 'datepicker')))
			->add('date_fin','date',array(
				'label' => "Date de fin d'affichage",
				'widget' => 'single_text',
				'input' => 'datetime',
				'format' => 'dd/MM/yyyy',
				'attr' => array('class' => 'datepicker')))
			->add('banner', 'entity', array(
			    'class' => 'CAFAdminBundle:Banner',
			    'query_builder' => 
			    	function(EntityRepository $er) {
				        return $er->createQueryBuilder('a')->where('a.published = 1')
	            										   ->orderBy('a.banner_name', 'ASC');
				    },
				'property' => 'banner_name',
				'multiple' => true
					)
				)
		;
	}

	public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\BlocBundle\Entity\BlocBannerRight');
    }

	public function getName() {
		return 'bloc_banner_right';
	}
}