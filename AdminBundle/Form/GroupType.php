<?php

namespace CAF\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;


class GroupType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{

		$builder
			->add('name')
			->add('role', 'choice', array(
					'choices'   => array('ROLE_ADMIN' => 'Administrator', 'ROLE_USER' => 'User'),
					)
				)

		;
	}

	public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\AdminBundle\Entity\Group');
    }
	
	public function getName() {
		return 'group';
	}
}

?>