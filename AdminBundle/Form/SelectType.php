<?php

namespace CAF\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\ORM\EntityRepository;


class SelectType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{

                $lang_id = $options["lang_id"];
            
                $builder->add('category', 'entity', array(
                            'class' => 'CAFContentBundle:CategoryTranslation',
                            'query_builder' => function(EntityRepository $er) use ($lang_id) {
                                    return $er->getCategoryLang($lang_id);
                                },
                            //'label' => 'Catégories ',                                                     
                            'property' => 'title',
                            'required'  => false,
                             'empty_value' => '-'            
                            ))
                           ->add('content', 'choice', array(
                            'choices'   => array(
                               '0' => '-',                        
                            ),
                            //'label' => 'Item ',                                                                               
                            'required'  => false
                                   
                            ))             
                
                ;
	}
        
	public function getDefaultOptions(array $options)
        {
            return array("lang_id"=>1, 'data_class' =>array('category'=>'', 'content'=>''));
        }
	
        public function getParent()
        {
            return 'form';
        }        
        
	public function setDefaultOptions(OptionsResolverInterface $resolver)
        {
            $resolver->setDefaults(array("lang_id"=>1));
        }    
    
	public function getName() {
		return 'select';
	}
}
