<?php

namespace LinkValue\MobileNotifBundle\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use LinkValue\MobileNotifBundle\Domain\Auto\ApplicationDomainTrait;
use LinkValue\MobileNotifBundle\Entity\Application;
use LinkValue\MobileNotifBundle\Entity\MobileClient\MobileClientInterface;
use LinkValue\MobileNotifBundle\Entity\MobileClient\MobileMessage;
use LinkValue\MobileNotifBundle\Event\ApplicationEvent;
use LinkValue\MobileNotifBundle\Event\ApplicationEvents;
use LinkValue\MobileNotifBundle\Repository\ApplicationRepositoryInterface;

/**
 * Application domain use cases class.
 *
 * Auto generated methods :
 *
 * @method create(Application $application)
 * @method edit(Application $application)
 * @method delete(Application $application)
 *
 * @property applicationRepository
 */
class ApplicationDomain
{
    use ApplicationDomainTrait;

    /**
     * @var ArrayCollection
     */
    protected $mobileClients;

    /**
     * construct.
     *
     * @param ApplicationRepositoryInterface $applicationRepository
     */
    public function __construct(ApplicationRepositoryInterface $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
        $this->mobileClients = new ArrayCollection();
    }

    /**
     * register a new mobile client under given key.
     *
     * @param string                $support
     * @param MobileClientInterface $mobileClient
     */
    public function registerMobileClient($support, MobileClientInterface $mobileClient)
    {
        $this->mobileClients->set(
            $support,
            $mobileClient
        );
    }

    /**
     * Push given message to given application.
     *
     * @param Application   $application
     * @param MobileMessage $message
     */
    public function push(Application $application, MobileMessage $message)
    {
        $mobileClient = $this->mobileClients->get($this->generateKey(
            $application->getType(),
            $application->getSupport()
        ));
        if (!$mobileClient) {
            throw new \RuntimeException(sprintf('Any mobile client defined for "%s", only [%s] are.',
                $application->getSupport(),
                implode(', ', $this->mobileClients->getKeys())
            ));
        }

        $mobileClient->push($message);
    }

    /**
     * Logout from given application.
     *
     * @param Application $application
     */
    public function logout(Application $application)
    {
        $this->fireEvent(
            ApplicationEvents::PUSHNOT_APPLICATION_LOGOUT,
            new ApplicationEvent($application)
        );
    }
}
