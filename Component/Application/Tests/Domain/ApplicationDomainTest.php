<?php

namespace PushNotification\Si\Component\Application\Tests\Domain;

use PushNotification\Si\Component\Application\Entity\Application;
use PushNotification\Si\Component\Application\Entity\MobileClient\MobileMessage;
use PushNotification\Si\Component\Application\Tests\Domain\Auto\ApplicationDomainTestTrait;
use PHPUnit_Framework_TestCase;

/**
 * Unit test class for ApplicationDomain.php.
 *
 * @see PushNotification\Si\Component\Application\Domain\ApplicationDomain
 * @see PushNotification\Si\Component\Application\Tests\Domain\Auto\ApplicationDomainTestTrait
 */
class ApplicationDomainTest extends PHPUnit_Framework_TestCase
{
    use ApplicationDomainTestTrait;

    /**
     * tests push() method throws an exception when wrong application type/support couple given.
     *
     * @dataProvider domainArgumentsProvider
     */
    public function testPushUnsupportedApplication($arguments)
    {
        $this->setExpectedException('RuntimeException');

        $application = new Application();
        $application
            ->setId(42)
            ->setType('unregisteredClientType')
            ->setSupport('unregisteredClientSupport')
        ;

        $this
            ->createDomain($arguments)
            ->push($application, new MobileMessage())
        ;
    }

    /**
     * tests push() method.
     *
     * @dataProvider domainArgumentsProvider
     */
    public function testPush($arguments)
    {
        $applicationType = 'someType';
        $applicationSupport = 'someSupport';

        $applicationDomain = $this->createDomain($arguments);

        $mobileClient = $this->prophesize('PushNotification\Si\Component\Application\Entity\MobileClient\MobileClientInterface');

        $applicationDomain->registerMobileClient(
            $applicationType,
            $applicationSupport,
            $mobileClient->reveal()
        );

        $application = (new Application())
            ->setId(42)
            ->setType($applicationType)
            ->setSupport($applicationSupport)
        ;

        $message = new MobileMessage();

        $applicationDomain->push($application, $message);

        $mobileClient->push($message)->shouldHaveBeenCalled();
    }
}
