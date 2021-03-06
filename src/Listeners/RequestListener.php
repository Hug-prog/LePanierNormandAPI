<?php

namespace App\Listeners;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class RequestListener implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return [
           KernelEvents::REQUEST => [
               ['onKernelRequest'],
           ],
        ];
    }

    public function onKernelRequest(RequestEvent $event) {
        $request = $event->getRequest();
        $request->attributes->set('refresh_token', $request->cookies->get('refresh_token'));
    }

}