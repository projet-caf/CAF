<?php

namespace CAF\BlocBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CAF\BlocBundle\Entity\BlocBannerRight
 *
 * @ORM\Table(name="blocbannerright")
 * @ORM\Entity
 */
class BlocBannerRight
{

	/**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
    * @ORM\ManyToOne(targetEntity="Bloc")
    */
    private $bloc;

    /**
    * @ORM\ManyToMany(targetEntity="CAF\AdminBundle\Entity\Banner")
    */
    private $banner;

    /**
     * @var date $date_debut
     *
     * @ORM\Column(name="date_debut", type="date")
     */
    private $date_debut;

    /**
     * @var date $date_fin
     *
     * @ORM\Column(name="date_fin", type="date")
     */
    private $date_fin;

    public function __construct(){
        $bloc = new Bloc();
        $this->setBloc($bloc);
    }

    public function html($path_web,$options=null){
        $now = date('Y-m-d');
        $now = new \DateTime($now);
        if($this->date_debut<=$now && $now<=$this->date_fin){
          // On prend une banniere aleatoirement
           $item = rand(0, count($this->banner)-1);
            
            $html = '<div class="ban_right">';
            $html .= '<a target="_bank" href="'.$this->banner[$item]->getLink().'?utm_source=CAF&utm_medium=banner&utm_content='.$this->banner[$item]->getAliasBannerName().'&utm_campaign='.$this->banner[$item]->getAliasCampaignName().'">';
            $html .= '<img src="'.$path_web.'/'.$this->banner[$item]->getshowfile().'"/>';
            $html .= '</a>';
            $html .= '</div>';
        }
        else{
            $html ="";
        }
       
        $this->html = $html;
        return $this;
    }

    public function getType(){
        return 'BlocBannerRight';
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set bloc
     *
     * @param CAF\BlocBundle\Entity\Bloc $bloc
     */
    public function setBloc(\CAF\BlocBundle\Entity\Bloc $bloc)
    {
        $this->bloc = $bloc;
    }

    /**
     * Get bloc
     *
     * @return CAF\BlocBundle\Entity\Bloc 
     */
    public function getBloc()
    {
        return $this->bloc;
    }


    /**
     * Set menu
     *
     * @param CAF\MenuBundle\Entity\MenuTaxonomy $menu
     */
    public function setMenu(\CAF\MenuBundle\Entity\MenuTaxonomy $menu)
    {
        $this->menu = $menu;
    }

    /**
     * Get menu
     *
     * @return CAF\MenuBundle\Entity\MenuTaxonomy 
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set banner
     *
     * @param CAF\AdminBundle\Entity\Banner $banner
     * @return BlocBannerRight
     */
    public function setBanner(\CAF\AdminBundle\Entity\Banner $banner = null)
    {
        $this->banner = $banner;
    
        return $this;
    }

    /**
     * Get banner
     *
     * @return CAF\AdminBundle\Entity\Banner 
     */
    public function getBanner()
    {
        return $this->banner;
    }

    /**
     * Set date_debut
     *
     * @param \DateTime $dateDebut
     * @return BlocBannerRight
     */
    public function setDateDebut($dateDebut)
    {
        $this->date_debut = $dateDebut;
    
        return $this;
    }

    /**
     * Get date_debut
     *
     * @return \DateTime 
     */
    public function getDateDebut()
    {
        return $this->date_debut;
    }

    /**
     * Set date_fin
     *
     * @param \DateTime $dateFin
     * @return BlocBannerRight
     */
    public function setDateFin($dateFin)
    {
        $this->date_fin = $dateFin;
    
        return $this;
    }

    /**
     * Get date_fin
     *
     * @return \DateTime 
     */
    public function getDateFin()
    {
        return $this->date_fin;
    }

    /**
     * Add banner
     *
     * @param CAF\AdminBundle\Entity\Banner $banner
     * @return BlocBannerRight
     */
    public function addBanner(\CAF\AdminBundle\Entity\Banner $banner)
    {
        $this->banner[] = $banner;
    
        return $this;
    }

    /**
     * Remove banner
     *
     * @param CAF\AdminBundle\Entity\Banner $banner
     */
    public function removeBanner(\CAF\AdminBundle\Entity\Banner $banner)
    {
        $this->banner->removeElement($banner);
    }
}