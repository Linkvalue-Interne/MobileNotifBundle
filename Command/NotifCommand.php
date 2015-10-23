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
            ->addArgument('client', InputArgument::REQUIRED, 'Mobile client to use (use the first one by default)')
            ->addArgument('device', InputArgument::REQUIRED, 'Token of the device which will receive the notification.')
            ->addArgument('message', InputArgument::REQUIRED, 'Notification message.')
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
        $mobileNotif->using($input->getArgument('client'));

        $message = new MobileMessage();
        $message
            ->setDeviceToken($input->getArgument('device'))
            ->setMessage($input->getArgument('message'))
        ;

        $mobileNotif->push($message);
    }
}
