<?php

namespace Soundvenirs\DomainBundle\Repository;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Selectable;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Soundvenirs\DomainBundle\Entity;

/**
 * A repository for Sound entities
 *
 * This is a repository in the Domain-driven Design sense, it is not an extension of Doctrine\ORM\EntityRepository.
 * It does use Doctrine\ORM\EntityRepository and delegates method calls to it, but it also handles entity creation and
 * persistance, making it the one-stop class for all entity-related operations.
 *
 * @package Soundvenirs\DomainBundle\Repository
 */
class Sound implements ObjectRepository, Selectable
{
    protected $doctrineEntityManager;
    protected $doctrineSoundRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->doctrineEntityManager = $entityManager;
        $this->doctrineSoundRepository = $entityManager->getRepository('Soundvenirs\DomainBundle\Entity\Sound');
    }

    public function find($id)
    {
        return $this->doctrineSoundRepository->find($id);
    }

    public function findAll()
    {
        return $this->doctrineSoundRepository->findAll();
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->doctrineSoundRepository->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function findOneBy(array $criteria)
    {
        return $this->doctrineSoundRepository->findOneBy($criteria);
    }

    public function getClassName()
    {
        return $this->doctrineSoundRepository->getClassName();
    }

    public function matching(Criteria $criteria)
    {
        return $this->doctrineSoundRepository->matching($criteria);
    }

    /**
     * @param $title The title of the sound
     * @param $forcedId Forced id - if this is a string, it's used as the id. If null, id is generated
     * @return \Soundvenirs\DomainBundle\Entity\Sound Sound entity
     */
    public function create($title, $forcedId = null)
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
        $sound->title = $title;
        return $sound;
    }

    /**
     * @param \Soundvenirs\DomainBundle\Entity\Sound $sound
     * @return void
     *
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function persist(Entity\Sound $sound)
    {
        $this->doctrineEntityManager->persist($sound);
        $this->doctrineEntityManager->flush($sound);
    }

    protected function isUnique($id)
    {
        $sound = $this->doctrineSoundRepository->find($id);
        if ($sound === null) {
            return true;
        } else {
            return false;
        }
    }

    protected function generateId()
    {
        return base_convert((string)rand(0, 2147483647), 10, 36);
    }
}
