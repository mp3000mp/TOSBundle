<?php

declare(strict_types=1);

namespace Mp3000mp\TOSBundle\EventSubscriber;

use App\Entity\User;
use Mp3000mp\TOSBundle\Service\Mp3000mpTOSService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

/**
 * Class LocaleSubscriber.
 */
class TermsOfServiceSubscriber implements EventSubscriberInterface
{
    /**
     * @var int
     */
    public static $priority = -20;

    /**
     * @var array
     */
    private $authenticators;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var Mp3000mpTOSService
     */
    private $TOSService;

    /**
     * DoubleAuthSubscriber constructor.
     */
    public function __construct(TokenStorageInterface $tokenStorage, RouterInterface $router, Mp3000mpTOSService $TOSService, array $authenticators)
    {
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
        $this->TOSService = $TOSService;
        $this->authenticators = $authenticators;
    }

    /**
     * @return array[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', self::$priority]],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        // if already on TOS
        if (in_array($event->getRequest()->attributes->get('_route'), Mp3000mpTOSService::ROUTES_TOS, true)) {
            return;
        }

        // if authenticated and not already TOS signed
        $currentToken = $this->tokenStorage->getToken();

        if ($currentToken instanceof PostAuthenticationGuardToken
           && in_array($currentToken->getProviderKey(), $this->authenticators, true)
           && !in_array(Mp3000mpTOSService::ROLE_TOS_SIGNED, $currentToken->getRoleNames(), true)
        ) {
            /**
             * @var UserInterface $currentUser
             */
            $currentUser = $currentToken->getUser();

            // if ok
            if (null !== $this->TOSService->getLastSignedTOS($currentUser)) {
                $this->TOSService->addTOSSignedRole($this->tokenStorage, $event->getRequest()->getSession());
            } else {
                // else redirect
                $response = new RedirectResponse($this->router->generate(Mp3000mpTOSService::ROUTES_TOS[0]));
                $event->setResponse($response);
            }
        }
    }
}
