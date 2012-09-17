<?php
namespace CAF\FrontBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\Routing\RouterInterface;
use Doctrine\ORM\EntityManager;
use CAF\FrontBundle\Entity\ErrorUrl;
use CAF\FrontBundle\Entity\Repository\ErrorUrlRepository;

class ErrorListener
{

    /**
     * @var TwigEngine
     */
    protected $templating;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ErrorUrl Repository
     **/
    protected $repository;



    /**
     * @param ContainerInterface $container
     */
    public function __construct(TwigEngine $templating, EntityManager $em, ErrorUrlRepository $repository){
        // assign value(s)
        $this->templating = $templating;
        $this->em = $em;
        $this->repository = $repository;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
	{
	    static $handling;

	    $exception = $event->getException();
	    if (true === $handling) {
	        return;
	    }
	    $handling = true;
	    $code = $exception->getStatusCode();
	    $message_exception = $exception->getMessage();
	    if (404 === $code) {
	    	$message_size = strlen('No route found for "GET ');
	    	$url_error = substr($message_exception,$message_size,strlen($message_exception)-$message_size-1);
	    	
	    	$errorUrlObject = $this->repository->findByUrl($url_error);
	    	$errorUrlObject = current($errorUrlObject);

	    	if(!is_object($errorUrlObject)) {
	    		$error_url = new ErrorUrl();
		    	$error_url->setUrl($url_error);
		    	$error_url->setCode($code);
		    	$this->em->persist($error_url);
		    	$this->em->flush();	
		    	$message = $this->templating->render('CAFFrontBundle::layout.html.twig', array('metas' => '', 'display_menu' => 1, 'lang' => 'fr', 'country' => 'fr', 'cat_id' => null, 'item_id' => null, 'cats' => null, 'path' => '', 'template' => 'error'));
		        $response = new Response($message, $code);
		        $event->setResponse($response);
	    	} else {
	    		$errorUrlObject->setLastAccessed();
	    		$nb = $errorUrlObject->getNb()+1;
	    		$errorUrlObject->setNb($nb);
	    		$this->em->persist($errorUrlObject);
	    		$this->em->flush();
	    		if($errorUrlObject->getUrlDest() != '') {
		    		$response = new RedirectResponse('/web/app.php'.$errorUrlObject->getUrlDest(),$errorUrlObject->getCode());
		    		$event->setResponse($response);
		    	}
		    }	
	    	

	        
	    }

	    $handling = false;
	}
}