<?php

namespace CAF\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

use CAF\ContentBundle\Form\EventListener\AddContentTaxonomyFieldsSubscriber;
use CAF\ContentBundle\Entity\FieldsRepository;


class ContentType extends AbstractType
{


	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$name = 'values'.ucfirst($options['lang']);
		$meta_name = 'metasValues'.ucfirst($options['lang']);
		$translation_name = 'translation'.ucfirst($options['lang']);
		$builder
			->add($translation_name, new ContentTranslationType(), array(
                'label' 	=> ' ', 'lang' => $options['lang'], 'lang_id' => $options['lang_id']
                ))
			->add($name, 'values', array('content_taxonomy' => $options['content_taxonomy'], 'label' => ' '))
			->add($meta_name, 'metasvalues', array('label' => ' ', 'lang' => $options['lang'], 'canonical' => $options['canonical']))
		;
		$builder->setAttribute('lang', $options['lang']);	
	}

	public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\ContentBundle\Entity\Content', 'lang' => 'fr', 'lang_id' => 1, 'content_taxonomy' => 5, 'canonical' => '');
    }
	
	public function getName() {
		return 'content';
	}
}

?>