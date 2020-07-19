<?php

namespace App\Security;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;

/**
 * API Authenticator.
 */
class ApiAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var Security
     */
    private $security;

    /**
     * @var LoggerInterface
     **/
    private $logger;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * Constructor.
     *
     * @param Security                     $security
     * @param LoggerInterface              $logger
     * @param UserPasswordEncoderInterface $encoder
     * @param TranslatorInterface          $translator
     */
    public function __construct(Security $security, LoggerInterface $logger, UserPasswordEncoderInterface $encoder, TranslatorInterface $translator)
    {
        $this->security = $security;
        $this->logger = $logger;
        $this->encoder = $encoder;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request)
    {
        // Pass with user access token.
        if ($request->headers->has('X-API-KEY')) {
            return [
                'token' => $request->headers->get('X-API-KEY'),
            ];
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (!isset($credentials['token'])) {
            return null;
        }
        
        $token = $credentials['token'];

        if (!$user = $userProvider->loadUserByUsername($token)) {
            return null;
        }
        
        return $userProvider->refreshUser($user);
    }

    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return $user->isEnabled();
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null; // on success, let the request continue
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $this->logger->info('Authentication failure.', [$exception->getMessage()]);

        $message = $this->translator->trans('authenticator.token.failure', [], 'auth');

        return new JsonResponse([
            'messages' => [
                'notice' => [$message],
            ],
        ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $exception = null)
    {
        return new JsonResponse([
            'messages' => [
                'notice' => [$exception->getMessage()],
            ],
        ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsRememberMe()
    {
        return false;
    }
}
