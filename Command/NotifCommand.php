<?php

namespace LinkValue\MobileNotifBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use LinkValue\MobileNotifBundle\Entity\MobileClient\MobileMessage;

class NotifCommand extends ContainerAwareCommand
{
    /**
     * Command configuration.
     */
    protected function configure()
    {
        $this
            ->setName('mobilenotif:push')
            ->setDescription('Push notification command.')
            ->addArgument('device_token', InputArgument::REQUIRED, 'Token of the device which will receive the notification.')
            ->addArgument('message', InputArgument::REQUIRED, 'Notification message.')
            ->addOption('mobile-client', 'm', InputOption::VALUE_REQUIRED, 'Mobile client to use (use the first one by default)', null)
        ;
    }

    /**
     * Command execution.
     *
     * @codeCoverageIgnore
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var $mobileNotif \LinkValue\MobileNotifBundle\MobileNotif */
        $mobileNotif = $this->getContainer()->get('link_value_mobile_notif.mobile_notif');

        $mobileClientKey = $input->getOption('mobile-client');
        if (!is_null($mobileClientKey)) {
            $mobileNotif->using($mobileClientKey);
        }

        $message = (new MobileMessage())
            ->setDeviceToken($input->getArgument('device_token'))
            ->setMessage($input->getArgument('message'))
        ;
        $mobileNotif->push($message);
    }
}
