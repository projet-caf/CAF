<?php

namespace CAF\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use CAF\ContentBundle\Entity\Repository\CategoryRepository;

class ContentTranslationType extends AbstractType
{



	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		/**/
		$lang_id = $options['lang_id'];
		$builder->add('title','text',array('label' => 'Titre', 'required' => false))
				->add('published', 'publish')
				->add('alias', 'text', array('required' => false))
				->add('categories', 'entity', array(
					'class' => 'CAFContentBundle:CategoryTranslation',
					'query_builder' => function(EntityRepository $er) use ($lang_id) {
					        return $er->getCategoryLang($lang_id);
					    },
					'multiple' => true,
					'label' => 'Catégories',
					))
				->add('countries','entity',array(
					'class' => 'CAFAdminBundle:Country',
					'multiple' => true,
					'property' => 'name',
					'label' => 'Pays',
					))
				
		;
	}

	public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'CAF\ContentBundle\Entity\ContentTranslation', 'content_taxonomy' => null);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'CAF\ContentBundle\Entity\ContentTranslation','content_taxonomy' => null, 'lang_id' => '', 'lang' => null));
    }
	
	public function getName() {
		return 'content_translation';
	}
}

?>