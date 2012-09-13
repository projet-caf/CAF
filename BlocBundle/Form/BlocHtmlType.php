<?php
namespace CAF\BlocBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use CAF\BlocBundle\Form\BlocType;

use Doctrine\ORM\EntityRepository;

class BlocHtmlType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('bloc', new BlocType)
			->add('content', 'ckeditor', array('label' => 'Contenu'))
		;
	}

	public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\BlocBundle\Entity\BlocHtml');
    }

	public function getName() {
		return 'bloc_html';
	}
}