<?php

/*
 * This file is part of the MobileNotifBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LinkValue\MobileNotifBundle\Command;

use LinkValue\MobileNotif\Model\GcmMessage;
use LinkValue\MobileNotifBundle\Client\GcmClient;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * GcmPushCommand.
 *
 * @package MobileNotifBundle
 * @author Oliver Thebault <oliver.thebault@gmail.com>
 */
class GcmPushCommand extends ContainerAwareCommand
{
    /**
     * Command configuration.
     */
    protected function configure()
    {
        $this
            ->setName('link_value_mobile_notif:gcm:push')
            ->setDescription('GCM Push notification command.')
            ->addArgument('token', InputArgument::REQUIRED, 'Token of the device which will receive the notification.')
            ->addArgument('message', InputArgument::REQUIRED, 'Notification message.')
            ->addOption('title', 't', InputOption::VALUE_REQUIRED, 'Notification title.', 'Title')
            ->addOption('icon', 'i', InputOption::VALUE_REQUIRED, 'Notification icon', 'myicon')
        ;
    }

    /**
     * Command execution.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $message = (new GcmMessage())
            ->setNotificationBody($input->getArgument('message'))
            ->setNotificationTitle($input->getOption('title'))
            ->setNotificationIcon($input->getOption('icon'))
            ->addToken($token = $input->getArgument('token'))
        ;

        $gcmClients = $this->getContainer()->get('link_value_mobile_notif.clients')->getGcmClients();

        if ($gcmClients->count() == 0) {
            throw new \RuntimeException('You must configure at least one GCM client to be able to push messages with this command.');
        }

        $gcmClients->forAll(function ($key, GcmClient $client) use ($message, $output) {
            $output->writeln(sprintf('Sending message "%s" using GCM [%s] client...', $message->getNotificationBody(), $key));
            $client->push($message);

            return true;
        });
    }
}
