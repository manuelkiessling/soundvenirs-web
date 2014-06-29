<?php

namespace Soundvenirs\DomainBundle\Tests\Repository;

use Soundvenirs\DomainBundle\Repository;

class SoundTest extends \PHPUnit_Framework_TestCase
{
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
}
