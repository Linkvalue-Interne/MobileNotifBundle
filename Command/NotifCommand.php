<?php

/*
 * This file is part of the MobileNotifBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LinkValue\MobileNotifBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use LinkValue\MobileNotif\Model\Message;

/**
 * ClientCollection
 *
 * @package MobileNotifBundle
 * @author  Jamal Youssefi <jamal.youssefi@gmail.com>
 * @author  Valentin Coulon <valentin.c0610@gmail.com>
 */
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
            ->addToken($input->getArgument('device'))
            ->setContent($input->getArgument('message'))
        ;

        $this->getContainer()->get('linkvalue.mobilenotif.clients')->get($input->getArgument('client'))->push($message);
    }
}
