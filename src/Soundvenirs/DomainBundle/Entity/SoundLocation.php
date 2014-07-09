<?php

namespace Soundvenirs\DomainBundle\Entity;

/**
 * The entity for locations of sounds that exist in the domain
 *
 * For now this looks identical to the Sound entity, but from a domain perspective,
 * they really are two different things: SoundLocations are a list of places the domain
 * knows about and makes available to the user. They share the same id for now, but this
 * might change in the future. Also, the Sound entity will grow with more attributes (artist etc.)
 * which the SoundLocation doesn't care about.
 *
 * @package Soundvenirs\DomainBundle\Entity
 */
class SoundLocation
{
    public $id;
    public $title;
    public $lat;
    public $long;
}
