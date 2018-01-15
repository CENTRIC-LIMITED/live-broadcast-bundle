<?php

namespace Martin1982\LiveBroadcastBundle\Command;

use React\EventLoop\Factory;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class BroadcasterCommand
 * @package Martin1982\LiveBroadcastBundle\Command
 */
class BroadcasterCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this->setName('livebroadcaster:broadcast')
            ->setDescription('Run any broadcasts that haven\'t started yet and which are planned');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Martin1982\LiveBroadcastBundle\Exception\LiveBroadcastException
     * @throws \Doctrine\ORM\Query\QueryException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \LogicException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $scheduler = $container->get('live.broadcast.scheduler');

        if (!$container->getParameter('livebroadcast.eventloop.enabled')) {
            $scheduler->applySchedule();

            return;
        }

        $eventLoop = Factory::create();
        $eventLoop->addPeriodicTimer(
            $container->getParameter('livebroadcast.eventloop.timer'),
            function () use ($scheduler) {
                $scheduler->applySchedule();
            }
        );

        $eventLoop->run();
    }
}
