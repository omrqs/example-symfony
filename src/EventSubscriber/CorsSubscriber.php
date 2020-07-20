<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Response;

/**
 * Subscriber for responses.
 */
class CorsSubscriber implements EventSubscriberInterface
{
    /**
     * Rebuild reponse with session messages. Include original response from controllers.
     */
    public function onKernelResponse(FilterResponseEvent $event): void
    {
        // Don't do anything if it's not the master request.
        if ($event->isMasterRequest()) {
            $response = $event->getResponse();

            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS, HEAD');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, X-API-KEY');
            $response->headers->set('X-Powered-By', getenv('APP_NAME'));

            $event->setResponse($response);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => [
                ['onKernelResponse', 10],
            ]
        ];
    }
}
