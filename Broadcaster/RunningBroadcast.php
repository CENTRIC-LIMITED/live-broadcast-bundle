<?php
declare(strict_types=1);

/**
 * This file is part of martin1982/livebroadcastbundle which is released under MIT.
 * See https://opensource.org/licenses/MIT for full license details.
 */
namespace Martin1982\LiveBroadcastBundle\Broadcaster;

use Martin1982\LiveBroadcastBundle\Entity\Channel\AbstractChannel;
use Martin1982\LiveBroadcastBundle\Entity\LiveBroadcast;

/**
 * Class RunningBroadcast
 */
class RunningBroadcast
{
    /**
     * @var int
     */
    private int $broadcastId;

    /**
     * @var int
     */
    private int $processId;

    /**
     * @var int
     */
    private int $channelId;

    /**
     * @var string
     */
    private string $environment;

    /**
     * RunningBroadcast constructor.
     *
     * @param int    $broadcastId
     * @param int    $processId
     * @param int    $channelId
     * @param string $environment
     */
    public function __construct(int $broadcastId, int $processId, int $channelId, string $environment)
    {
        $this->broadcastId = $broadcastId;
        $this->processId = $processId;
        $this->channelId = $channelId;
        $this->environment = $environment;
    }

    /**
     * @return int
     */
    public function getBroadcastId(): int
    {
        return $this->broadcastId;
    }

    /**
     * @return int
     */
    public function getProcessId(): int
    {
        return $this->processId;
    }

    /**
     * @return int
     */
    public function getChannelId(): int
    {
        return $this->channelId;
    }

    /**
     * @return string
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * @param LiveBroadcast   $broadcast
     * @param AbstractChannel $channel
     *
     * @return bool
     */
    public function isBroadcasting(LiveBroadcast $broadcast, AbstractChannel $channel): bool
    {
        return ($this->broadcastId === $broadcast->getBroadcastId()) && ($this->channelId === $channel->getChannelId());
    }

    /**
     * @param string $kernelEnvironment
     *
     * @return bool
     */
    public function isValid(string $kernelEnvironment): bool
    {
        return ($kernelEnvironment === $this->getEnvironment()) &&
            ($this->getProcessId() !== 0) &&
            ($this->getBroadcastId() !== 0) &&
            ($this->getChannelId() !== 0);
    }
}
