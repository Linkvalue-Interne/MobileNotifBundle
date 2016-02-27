<?php

/*
 * This file is part of the MobileNotifBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LinkValue\MobileNotifBundle\Command;

use LinkValue\MobileNotif\Model\ApnsMessage;
use LinkValue\MobileNotifBundle\Client\ApnsClient;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ApnsPushCommand.
 *
 * @package MobileNotifBundle
 * @author Oliver Thebault <oliver.thebault@gmail.com>
 */
class ApnsPushCommand extends ContainerAwareCommand
{
    /**
     * Command configuration.
     */
    protected function configure()
    {
        $this
            ->setName('link_value_mobile_notif:apns:push')
            ->setDescription('APNS Push notification command.')
            ->addArgument('token', InputArgument::REQUIRED, 'Token of the device which will receive the notification.')
            ->addArgument('message', InputArgument::REQUIRED, 'Notification message.')
            ->addOption('badge', 'b', InputOption::VALUE_REQUIRED, 'Notification badge', 0)
        ;
    }

    /**
     * Command execution.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $message = (new ApnsMessage())
            ->setSimpleAlert($input->getArgument('message'))
            ->setBadge($input->getOption('badge'))
            ->addToken($token = $input->getArgument('token'))
        ;

        $apnsClients = $this->getContainer()->get('link_value_mobile_notif.clients')->getApnsClients();

        if ($apnsClients->count() == 0) {
            throw new \RuntimeException('You must configure at least one APNS client to be able to push messages with this command.');
        }

        $apnsClients->forAll(function ($key, ApnsClient $client) use ($message, $output) {
            $output->writeln(sprintf('Sending message "%s" using APNS [%s] client...', $message->getSimpleAlert(), $key));
            $client->push($message);

            return true;
        });
    }
}
