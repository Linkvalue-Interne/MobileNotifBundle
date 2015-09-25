<?php

namespace PushNotification\Si\Component\Application\Domain\Auto;

use PushNotification\Si\Component\Application\Entity\Application;
use PushNotification\Si\Component\Application\Event\ApplicationEvent;
use PushNotification\Si\Component\Application\Event\ApplicationEvents;
use PushNotification\Si\Component\Application\Repository\ApplicationRepositoryInterface;

/**
 * Application domain use cases auto generated trait.
 *
 * @codeCoverageIgnore
 *
 * @see DomainTrait::assertEntityIsValid()
 * @see DomainTrait::fireEvent()
 */
trait ApplicationDomainTrait
{
    protected $applicationRepository;

    /**
     * construct.
     *
     * @param ApplicationRepositoryInterface $applicationRepository
     */
    public function __construct(
        ApplicationRepositoryInterface $applicationRepository
    ) {
        $this->applicationRepository = $applicationRepository;
    }

    /**
     * Process given Application creation process.
     *
     * @param Application $application
     */
    public function create(Application $application)
    {
        $this->assertEntityIsValid($application, 'creation');

        $this->applicationRepository->save($application);

        $this->fireEvent(
            ApplicationEvents::PUSHNOT_APPLICATION_CREATED,
            new ApplicationEvent($application)
        );
    }

    /**
     * Process given Application edition process.
     *
     * @param Application $application
     */
    public function edit(Application $application)
    {
        $this->assertEntityIsValid($application, 'edition');

        $this->applicationRepository->save($application);

        $this->fireEvent(
            ApplicationEvents::PUSHNOT_APPLICATION_EDITED,
            new ApplicationEvent($application)
        );
    }

    /**
     * Enable given Application.
     *
     * @param Application $application
     */
    public function enable(Application $application)
    {
        return $this->edit(
            $application->enable()
        );
    }

    /**
     * Disable given Application.
     *
     * @param Application $application
     */
    public function disable(Application $application)
    {
        return $this->edit(
            $application->disable()
        );
    }

    /**
     * Process given Application deletion process.
     *
     * @param Application $application
     */
    public function delete(Application $application)
    {
        $this->assertEntityIsValid($application, 'deletion');

        $this->applicationRepository->delete($application);

        $this->fireEvent(
            ApplicationEvents::PUSHNOT_APPLICATION_DELETED,
            new ApplicationEvent($application)
        );
    }
}
