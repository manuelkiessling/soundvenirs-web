<?php

namespace Soundvenirs\DomainBundle\Factory;

use Doctrine\ORM\EntityRepository;
use \Soundvenirs\DomainBundle\Entity;

class Sound
{
    protected $soundRepository;

    protected function generateId()
    {
        return base_convert((string)rand(0, 2147483647), 10, 36);
    }

    protected function isUnique($id)
    {
        $sound = $this->soundRepository->find($id);
        if ($sound === null) {
            return true;
        } else {
            return false;
        }
    }

    public function __construct(EntityRepository $soundRepository)
    {
        $this->soundRepository = $soundRepository;
    }

    public function create($forcedId = null)
    {
        if (is_string($forcedId)) {
            $id = $forcedId;
        } else {
            $id = $this->generateId();
        }

        while (!$this->isUnique($id)) {
            $id = $this->generateId();
        }

        $sound = new Entity\Sound();
        $sound->id = $id;
        return $sound;
    }
}
