<?php
namespace CAF\ContentBundle\Form\EventListener;

use Symfony\Component\Form\Event\DataEvent;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvents;
use CAF\AdminBundle\Entity\FieldsRepository;

class MetasValuesSubscriber implements EventSubscriberInterface
{
    private $factory;
    private $metas;
    private $lang;
    private $canonical;

    public function __construct(FormFactoryInterface $factory, array $metas, $lang, $canonical)
    {
        $this->factory = $factory;
        $this->metas = $metas;
        $this->lang = $lang;
        $this->canonical = $canonical;
    }

    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that we want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    public function preSetData(DataEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        // During form creation setData() is called with null as an argument
        // by the FormBuilder constructor. We're only concerned with when
        // setData is called with an actual Entity object in it (whether new,
        // or fetched with Doctrine). This if statement let's us skip right
        // over the null condition.
        if (null === $data) {
            return;
        }

        foreach($this->metas as $meta) {
            $name = $meta->getName();
            switch($name) {
                case 'Content-Language':
                    $form->add($this->factory->createNamed($meta->getName(),$meta->getDisplay(),null,array('required' => false, 'data' => $this->lang)));
                    break;
                case 'Content-type':
                    $form->add($this->factory->createNamed($meta->getName(),$meta->getDisplay(),null,array('required' => false, 'data' => 'text/html; charset=utf-8')));
                    break;
                case 'Robots':
                    $form->add(
                        $this->factory->createNamed(
                            $meta->getName(),
                            'choice',
                            null,
                            array(
                                'required' => false,
                                'choices' => array('all' => 'all', 'none' => 'none', 'index,follow' => 'index,follow', 'index,nofollow' => 'index,nofollow', 'noindex,follow' => 'noindex,follow', 'noindex,nofollow' => 'noindex,nofollow'),
                                'data' => 'index,follow'
                            )
                        )
                    );
                    break;
                case 'Canonical':
                    $form->add($this->factory->createNamed($meta->getName(),$meta->getDisplay(),null,array('required' => false, 'data' => $this->canonical)));   
                    break;
                default: 
                    $form->add($this->factory->createNamed($meta->getName(),$meta->getDisplay(),null,array('required' => false)));
                    break;     
            }
           
                
        }
    }
}