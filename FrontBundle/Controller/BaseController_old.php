<?php

namespace CAF\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CAF\AdminBundle\Entity\Menu;

class BaseController extends Controller
{
    /**
     * @Route("/{lang}/{country}/{category}/{content}", requirements={"lang" = "fr|en|de"}, defaults={"category" = "", "content" = ""})
     * @Template()
     */
    public function indexAction($lang, $country, $category, $content)
    {
        return array('lang' => $lang, 'country' => $country);
    }

}
