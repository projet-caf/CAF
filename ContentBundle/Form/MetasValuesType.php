<?php

namespace CAF\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use CAF\ContentBundle\Form\EventListener\MetasValuesSubscriber;


class MetasValuesType extends AbstractType
{
	private $repository;

	public function __construct(EntityRepository $repository) {
		$this->repository = $repository;
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$metas = $this->repository->getMetas();
		$lang = $options['lang'];
		$canonical = $options['canonical'];
		$subscriber = new MetasValuesSubscriber($builder->getFormFactory(), $metas, $lang, $canonical);
        $builder->addEventSubscriber($subscriber);
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('content_taxonomy' => null, 'lang_id' => '', 'lang' => null, 'canonical' => null));
    }

	public function getName() {
		return 'metasvalues';
	}
}
