<?php

namespace Soundvenirs\DomainBundle\Tests\Repository;

use Soundvenirs\DomainBundle\Entity\Sound;
use Soundvenirs\DomainBundle\Repository;

class SoundLocationTest extends \PHPUnit_Framework_TestCase
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

    public function testGetAll()
    {
        $sound1 = new Sound();
        $sound1->setId('abcdef');
        $sound1->setTitle('Foo Bar');
        $sound1->setLat(11.1);
        $sound1->setLong(1.11);
        $sound2 = new Sound();
        $sound2->setId('ghijkl');
        $sound2->setTitle('Woomp');
        $sound2->setLat(22.2);
        $sound2->setLat(2.22);

        $mockQuery = $this->getMock('stdClass', array('getResult'));
        $mockQuery->expects($this->once())
            ->method('getResult')
            ->with()
            ->will($this->returnValue(array($sound1, $sound2)));

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

        $soundLocationRepo = new Repository\SoundLocation($this->doctrineEntityManager);
        $soundLocations = $soundLocationRepo->getAll();
        $this->assertSame('abcdef', $soundLocations[0]->id);
        $this->assertSame('Woomp', $soundLocations[1]->title);
    }
}
