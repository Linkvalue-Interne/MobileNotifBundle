<?php

namespace LinkValue\MobileNotifBundle\Tests\Domain\Auto;

use LinkValue\MobileNotifBundle\Entity\Application;
use LinkValue\MobileNotifBundle\Event\ApplicationEvent;
use LinkValue\MobileNotifBundle\Event\ApplicationEvents;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * Tests traits for ApplicationDomainTest PHPUnit test case class.
 *
 */
trait ApplicationDomainTestTrait
{
    /**
     * Argument provider for domain creation.
     *
     * @return array()
     */
    public function domainArgumentsProvider()
    {
        return array(array(array(
            'repository' => $this->prophesize('LinkValue\MobileNotifBundle\Repository\ApplicationRepositoryInterface'),
            'event_dispatcher' => $this->prophesize('Symfony\Component\EventDispatcher\EventDispatcherInterface'),
            'validator' => $this->prophesize('Symfony\Component\Validator\Validator\ValidatorInterface'),
        )));
    }

    /**
     * create domain.
     */
    public function createDomain($arguments)
    {
        $eventDispatcher = $arguments['event_dispatcher'];
        $validator = $arguments['validator'];

        unset($arguments['event_dispatcher']);
        unset($arguments['validator']);

        $domain = (new \ReflectionClass('LinkValue\MobileNotifBundle\Domain\ApplicationDomain'))
            ->newInstanceArgs(array_map(function ($class) {
                return $class instanceof ObjectProphecy ?
                    $class->reveal() : $class
                ;
            }, array_values($arguments)))
        ;
        $domain->setEventDispatcher($eventDispatcher->reveal());
        $domain->setValidator($validator->reveal());

        return $domain;
    }

    /**
     * tests create() method.
     *
     * @dataProvider domainArgumentsProvider
     */
    public function testCreate($arguments)
    {
        $application = new Application();
        $application->setId(42);

        $repository = $arguments['repository'];
        $repository->save($application)->shouldBeCalled();

        $asserter = $this;

        $eventDispatcher = $arguments['event_dispatcher'];
        $eventDispatcher
            ->dispatch(
                ApplicationEvents::PUSHNOT_APPLICATION_CREATED,
                new ApplicationEvent($application)
            )
            ->will(function ($args) use ($application, $asserter) {
                $asserter->assertEquals($application, $args[1]->getApplication());
                $asserter->assertEquals(array('application_id' => 42), $args[1]->getData());
            })
            ->shouldBeCalled()
        ;

        $validator = $arguments['validator'];
        $validator->validate($application, null, array('creation'))->shouldBeCalled();

        $this->createDomain($arguments)
            ->create($application)
        ;
    }

    /**
     * tests edit() method.
     *
     * @dataProvider domainArgumentsProvider
     */
    public function testEdit($arguments)
    {
        $application = new Application();
        $application->setId(42);

        $repository = $arguments['repository'];
        $repository->save($application)->shouldBeCalled();

        $eventDispatcher = $arguments['event_dispatcher'];
        $eventDispatcher
            ->dispatch(
                ApplicationEvents::PUSHNOT_APPLICATION_EDITED,
                new ApplicationEvent($application)
            )
            ->shouldBeCalled()
        ;

        $validator = $arguments['validator'];
        $validator->validate($application, null, array('edition'))->shouldBeCalled();

        $this->createDomain($arguments)
            ->edit($application)
        ;
    }

    /**
     * tests delete() method.
     *
     * @dataProvider domainArgumentsProvider
     */
    public function testDelete($arguments)
    {
        $application = new Application();
        $application->setId(42);

        $repository = $arguments['repository'];
        $repository->delete($application)->shouldBeCalled();

        $eventDispatcher = $arguments['event_dispatcher'];
        $eventDispatcher
            ->dispatch(
                ApplicationEvents::PUSHNOT_APPLICATION_DELETED,
                new ApplicationEvent($application)
            )
            ->shouldBeCalled()
        ;

        $validator = $arguments['validator'];
        $validator->validate($application, null, array('deletion'))->shouldBeCalled();

        $this->createDomain($arguments)
            ->delete($application)
        ;
    }
}
