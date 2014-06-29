<?php

namespace Soundvenirs\DomainBundle\Tests\Repository;

use Soundvenirs\DomainBundle\Repository;

class SoundTest extends \PHPUnit_Framework_TestCase
{
    protected $doctrineSoundRepo;
    protected $doctrineEntityManager;

    public function setUp()
    {
        $doctrineSoundRepo = $this->getMockBuilder('\Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $doctrineEntityManager = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        $doctrineEntityManager->expects($this->once())
            ->method('getRepository')
            ->with('Soundvenirs\DomainBundle\Entity\Sound')
            ->will($this->returnValue($doctrineSoundRepo));

        $this->doctrineSoundRepo = $doctrineSoundRepo;
        $this->doctrineEntityManager = $doctrineEntityManager;
    }

    public function testCreate()
    {
        $soundRepo = new Repository\Sound($this->doctrineEntityManager);
        $sound = $soundRepo->create('foo');
        $this->assertRegExp('/^[0-9a-z]{1,6}$/', $sound->id);
        $this->assertSame('foo', $sound->title);
    }

    public function testPersist()
    {
        $soundRepo = new Repository\Sound($this->doctrineEntityManager);
        $sound = $soundRepo->create('foo');

        $this->doctrineEntityManager->expects($this->once())
            ->method('persist')
            ->with($sound);
        $this->doctrineEntityManager->expects($this->once())
            ->method('flush')
            ->with($sound);

        $soundRepo->persist($sound);
    }

    public function testGetConsumableSounds()
    {
        $mockQuery = $this->getMock('stdClass', array('getResult'));
        $mockQuery->expects($this->once())
            ->method('getResult')
            ->with()
            ->will($this->returnValue('foo'));

        $mockQueryBuilder = $this->getMock('stdClass', array('where', 'getQuery'));
        $mockQueryBuilder->expects($this->once())
            ->method('where')
            ->with('s.lat IS NOT NULL AND s.long IS NOT NULL')
            ->will($this->returnValue($mockQueryBuilder));

        $mockQueryBuilder->expects($this->once())
            ->method('getQuery')
            ->with()
            ->will($this->returnValue($mockQuery));

        $this->doctrineSoundRepo->expects($this->once())
            ->method('createQueryBuilder')
            ->with('s')
            ->will($this->returnValue($mockQueryBuilder));

        $soundRepo = new Repository\Sound($this->doctrineEntityManager);
        $actual = $soundRepo->getConsumableSounds();
        $this->assertSame('foo', $actual);
    }
}
