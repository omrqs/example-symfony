<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Subscriber for api requests.
 */
class ApiRequestSubscriber implements EventSubscriberInterface
{
    /**
     * Parser attributes from request to json format.
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        // default converter json to form data request.
        $data = [];
        if (0 === strpos($event->getRequest()->headers->get('Content-Type'), 'application/json')) {
            $data = json_decode($event->getRequest()->getContent(), true);
            $event->getRequest()->request->replace(is_array($data) ? $data : []);
            unset($data);
        }

        // Convert data snake case to camelCase and replace data request.
        $requestData = $event->getRequest()->request->all();
        $data = \App\Helper\CoreHelper::denormalize($requestData);

        $event->getRequest()->request->replace(is_array($data) ? $data : []);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
           'kernel.request' => 'onKernelRequest',
        ];
    }
}
