<?php
namespace CAF\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CAF\BlocBundle\Entity\BlocMenu
 *
 * @ORM\Table(name="error_url")
 * @ORM\Entity(repositoryClass="CAF\FrontBundle\Entity\Repository\ErrorUrlRepository")
 */
class ErrorUrl 
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
     * @var string $url
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

     /**
     * @var int $code
     *
     * @ORM\Column(name="code", type="integer", length=3)
     */
    private $code;

    /**
     * @var string $url_dest
     *
     * @ORM\Column(name="url_dest", type="string", length=255, nullable=true)
     */
    private $url_dest;


    /**
     * @var integer $nb
     * @ORM\Column(name="nb", type="integer")
     */
    protected $nb;

    /**
     * @var \DateTime $lastAccessed
     * @ORM\Column(name="last_accessed", type="datetime")
     */
    protected $lastAccessed;
    

    public function getId() {
    	return $this->id;
    }

    public function getUrl() {
    	return $this->url;
    }

    public function getCode() {
    	return $this->code;
    }

    public function getUrlDest() {
    	return $this->url_dest;
    }

    public function setUrl($url) {
    	$this->url = $url;
    	return $this;
    }

    public function setCode($code) {
    	$this->code = $code;
    	return $this;
    }
    public function setUrlDest($url_dest) {
    	$this->url_dest = $url_dest;
    	return $this;
    }

    /**
     * Set nb
     *
     * @param integer $nb
     * @return ErrorUrl
     */
    public function setNb($nb)
    {
        $this->nb = $nb;
    
        return $this;
    }

    /**
     * Get nb
     *
     * @return integer 
     */
    public function getNb()
    {
        return $this->nb;
    }

    /**
     * Set lastAccessed
     *
     * @param \DateTime $lastAccessed
     * @return ErrorUrl
     */
    public function setLastAccessed($lastAccessed='')
    {
        if($lastAccessed != '')
            $this->lastAccessed = $lastAccessed;
        else {
            $this->lastAccessed = new \DateTime("now");
        }
    
        return $this;
    }

    /**
     * Get lastAccessed
     *
     * @return \DateTime 
     */
    public function getLastAccessed()
    {
        return $this->lastAccessed;
    }
}