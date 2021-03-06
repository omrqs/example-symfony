<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Subscriber for responses.
 */
class KernelSubscriber implements EventSubscriberInterface
{
    /**
     * Rebuild reponse with session messages. Include original response from controllers.
     */
    public function onKernelResponse(FilterResponseEvent $event): void
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        if ('application/json' == $response->headers->get('content_type')) {
            $sessionMessages = $request->getSession()->getFlashbag()->all();
            if (0 == count($sessionMessages)) {
                $sessionMessages = [];
            }
            
            $content = !empty($response->getContent()) ? json_decode($response->getContent(), true) : [];

            // Concat messages when already exists.
            $messages = (isset($content['messages']))
                ? array_merge($content['messages'], $sessionMessages)
                : $sessionMessages
            ;

            $data = [];
            if (isset($content['data'])) {
                $data = $content;
                $data['messages'] = $messages;
            } else {
                $data['data'] = $content;
                $data['messages'] = $messages;
            }

            unset($content, $sessionMessages, $messages);

            if (isset($data['data']['messages'])) {
                unset($data['data']['messages']);
            }

            // Cleanup empty values and return parsed response.
            $data = array_filter($data);
            if (!empty($data)) {
                $response->setContent((string) json_encode($data));
            }
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
