<?php

namespace Soundvenirs\SoundBundle\Tests\Factory;

use Soundvenirs\SoundBundle\Factory;

class SoundTest extends \PHPUnit_Framework_TestCase
{
    public function testCreation()
    {
        $soundRepo = $this->getMockBuilder('\Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $soundRepo->expects($this->once())
            ->method('find')
            ->will($this->returnValue(null));
        $soundFactory = new Factory\Sound($soundRepo);
        $sound = $soundFactory->create();
        $this->assertRegExp('/^[0-9a-z]{1,6}$/', $sound->id);
    }

    public function testCreationWithForcedId()
    {
        $soundRepo = $this->getMockBuilder('\Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $soundRepo->expects($this->once())
            ->method('find')
            ->will($this->returnValue(null));
        $soundFactory = new Factory\Sound($soundRepo);
        $sound = $soundFactory->create('123456');
        $this->assertSame('123456', $sound->id);
    }

    public function testCollisionResolution()
    {
        $soundRepo = $this->getMockBuilder('\Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $soundRepo->expects($this->at(0))
            ->method('find')
            ->with('123456')
            ->will($this->returnValue(true));
        $soundRepo->expects($this->at(1))
            ->method('find')
            ->will($this->returnValue(null));
        $soundFactory = new Factory\Sound($soundRepo);
        $sound = $soundFactory->create('123456');
        $this->assertNotSame('123456', $sound->id);
        $this->assertRegExp('/^[0-9a-z]{1,6}$/', $sound->id);
    }
}
