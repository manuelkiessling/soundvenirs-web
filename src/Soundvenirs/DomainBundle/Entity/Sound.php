<?php

namespace Soundvenirs\DomainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Sound
{
    /**
     * @ORM\Column(type="string", length=6)
     * @ORM\Id
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=128)
     */
    protected $title;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $lat;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $long;


    /**
     * Set id
     *
     * @param string $id
     * @return Sound
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Sound
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set lat
     *
     * @param float $lat
     * @return Sound
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return float 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set long
     *
     * @param float $long
     * @return Sound
     */
    public function setLong($long)
    {
        $this->long = $long;

        return $this;
    }

    /**
     * Get long
     *
     * @return float 
     */
    public function getLong()
    {
        return $this->long;
    }
}
