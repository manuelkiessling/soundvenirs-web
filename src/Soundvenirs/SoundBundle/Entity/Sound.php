<?php

namespace Soundvenirs\SoundBundle\Entity;

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
    public $id;

    /**
     * @ORM\Column(type="string", length=128)
     */
    public $title;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $lat;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $long;
}
