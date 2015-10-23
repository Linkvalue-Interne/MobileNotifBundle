<?php

namespace LinkValue\MobileNotifBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use LinkValue\MobileNotifBundle\Model\Message;

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
        $message = new Message();
        $message
            ->setDeviceToken($input->getArgument('device'))
            ->setMessage($input->getArgument('message'))
        ;

        $this->getContainer()->get('linkvalue.mobilenotif.clients')->get($input->getArgument('client'))->push($message);
    }
}
