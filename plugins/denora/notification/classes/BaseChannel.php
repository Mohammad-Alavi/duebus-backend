<?php namespace Denora\Notification\Classes;

abstract class BaseChannel
{
    protected $event;

    public function __construct(BaseEvent $event)
    {
        $this->event = $event;

        $this->fire();
    }

    abstract protected function fire();

}
