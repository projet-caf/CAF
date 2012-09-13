<?php

namespace CAF\FormBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Doctrine\ORM\EntityRepository;
use CAF\FormBundle\Form\EventListener\ValuesSubscriber;


class ValuesType extends AbstractType
{
	private $repository;

	public function getDefaultOptions(array $options)
    {
        return array('form_taxonomy' => null);
    }


	public function __construct(EntityRepository $repository) {
		$this->repository = $repository;
		
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$fields = $this->repository->getFormFields(intval($options['form_taxonomy']));
		
		$subscriber = new ValuesSubscriber($builder->getFormFactory(), $fields);
        $builder->addEventSubscriber($subscriber);
	}

	
	public function getName() {
		return 'valuesform';
	}
}
