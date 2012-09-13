<?php

namespace CAF\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Doctrine\ORM\EntityRepository;
use CAF\ContentBundle\Form\EventListener\ValuesSubscriber;


class ValuesType extends AbstractType
{
	private $repository;

	public function getDefaultOptions(array $options)
    {
        return array('content_taxonomy' => null);
    }


	public function __construct(EntityRepository $repository) {
		$this->repository = $repository;
		
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$fields = $this->repository->getFields(intval($options['content_taxonomy']));
		
		$subscriber = new ValuesSubscriber($builder->getFormFactory(), $fields);
        $builder->addEventSubscriber($subscriber);
	}

	
	public function getName() {
		return 'values';
	}
}
