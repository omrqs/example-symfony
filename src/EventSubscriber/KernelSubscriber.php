<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * Subscriber for responses.
 */
class KernelSubscriber implements EventSubscriberInterface
{
    /**
     * Rebuild reponse with session messages. Include original response from controllers.
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        if ('application/json' == $response->headers->get('content_type')) {
            $sessionMessages = $request->getSession()->getFlashbag()->all();
            if (0 == count($sessionMessages)) {
                $sessionMessages = [];
            }
            
            $content = json_decode($response->getContent(), true);

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

            $response->setContent(json_encode($data));
            $event->setResponse($response);
        }

        // Set user locale on session request.
        if (is_null($request->getLocale())) {
            $request->setLocale('pt_BR');
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'kernel.response' => 'onKernelResponse',
        ];
    }
}
