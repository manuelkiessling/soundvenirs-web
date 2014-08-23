<?php

namespace Soundvenirs\DomainBundle\Repository;

use Doctrine\ORM\EntityManager;
use Soundvenirs\DomainBundle\Entity;

class SoundLocation
{
    protected $doctrineEntityManager;
    protected $doctrineSoundRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->doctrineEntityManager = $entityManager;
        $this->doctrineSoundRepository = $entityManager->getRepository('Soundvenirs\DomainBundle\Entity\Sound');
    }

    public function getAll()
    {
        $soundLocations = array();
        $query = $this->doctrineSoundRepository->createQueryBuilder('s')
            ->where('s.lat IS NOT NULL AND s.long IS NOT NULL')
            ->getQuery();
        $sounds = $query->getResult();
        foreach ($sounds as $sound) {
            $soundLocation = new Entity\SoundLocation();
            $soundLocation->id = $sound->getId();
            $soundLocation->title = $sound->getTitle();
            $soundLocation->lat = $sound->getLat();
            $soundLocation->long = $sound->getLong();
            $soundLocations[] = $soundLocation;
        }
        return $soundLocations;
    }
}
