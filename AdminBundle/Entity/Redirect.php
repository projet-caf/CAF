<?php
namespace CAF\AdminBundle\Entity;

use Zenstruck\Bundle\RedirectBundle\Entity\Redirect as BaseRedirect;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="redirect")
 */
class Redirect extends BaseRedirect
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="source", type="string", length=255)
     */
    protected $source;


    /**
     * @ORM\Column(name="destination", type="string", length=255)
     */
    protected $destination;

    /**
     * @var integer $status_code
     * @ORM\Column(name="status_code", type="integer")
     */
    protected $status_code;

    /**
     * @var integer $count
     * @ORM\Column(name="count", type="integer")
     */
    protected $count;

    /**
     * @var \DateTime $lastAccessed
     * @ORM\Column(name="last_accessed", type="datetime")
     */
    protected $lastAccessed;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function getStatusCode()
    {
        return $this->status_code;
    }

    /**
     * Set source
     *
     * @param string $source
     * @return Redirect
     */
    public function setSource($source)
    {
        $this->source = $source;
    
        return $this;
    }

    /**
     * Get source
     *
     * @return string 
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set destination
     *
     * @param string $destination
     * @return Redirect
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;
    
        return $this;
    }

    /**
     * Get destination
     *
     * @return string 
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Set status_code
     *
     * @param integer $statusCode
     * @return Redirect
     */
    public function setStatusCode($statusCode)
    {
        $this->status_code = $statusCode;
    
        return $this;
    }

    /**
     * Set count
     *
     * @param integer $count
     * @return Redirect
     */
    public function setCount($count)
    {
        $this->count = $count;
    
        return $this;
    }

    /**
     * Get count
     *
     * @return integer 
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set lastAccessed
     *
     * @param \DateTime $lastAccessed
     * @return Redirect
     */
    public function setLastAccessed($lastAccessed)
    {
        $this->lastAccessed = $lastAccessed;
    
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