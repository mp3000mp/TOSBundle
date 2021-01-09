<?php

declare(strict_types=1);

namespace Mp3000mp\TOSBundle\Tests\EventSubscriber;

use Mp3000mp\TOSBundle\Entity\TermsOfService;
use Mp3000mp\TOSBundle\EventSubscriber\TermsOfServiceSubscriber;
use Mp3000mp\TOSBundle\Service\Mp3000mpTOSService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

/**
 * Class LocaleSubscriber.
 */
class TermsOfServiceSubscriberTest extends TestCase
{
    /**
     * @var string[]
     */
    private $authenticators = ['main'];

    /**
     * @var TokenStorageInterface|MockObject
     */
    private $tokenStorage;

    /**
     * @var RouterInterface|MockObject
     */
    private $router;

    /**
     * @var Mp3000mpTOSService|MockObject
     */
    private $TOSService;

    /**
     * @var RequestEvent|MockObject
     */
    private $event;

    public function setup(): void
    {
        $this->tokenStorage = $this->getMockBuilder(TokenStorageInterface::class)->getMock();
        $this->router = $this->getMockBuilder(RouterInterface::class)->getMock();
        $this->TOSService = $this->getMockBuilder(Mp3000mpTOSService::class)->disableOriginalConstructor()->getMock();
        $this->event = $this->getMockBuilder(RequestEvent::class)->disableOriginalConstructor()->getMock();
    }

    public function testGetSubscribedEvents(): void
    {
        $r = TermsOfServiceSubscriber::getSubscribedEvents();

        self::assertEquals($r, [
            KernelEvents::REQUEST => [['onKernelRequest', -20]],
        ]);
    }

    public function testOnKernelRequestNotMasterRequest(): void
    {
        $this->tokenStorage->expects(self::never())
            ->method('getToken');
        $this->event->expects(self::once())
            ->method('isMasterRequest')
            ->willReturn(false);

        $eventSubscriber = new TermsOfServiceSubscriber($this->tokenStorage, $this->router, $this->TOSService, $this->authenticators);
        $eventSubscriber->onKernelRequest($this->event);
    }

    public function testOnKernelRequestAlreadyOnTOS(): void
    {
        $this->tokenStorage->expects(self::never())
            ->method('getToken');

        $bag = $this->getMockBuilder(ParameterBag::class)->getMock();
        $bag->expects(self::once())
            ->method('get')
            ->willReturn(Mp3000mpTOSService::ROUTES_TOS[0]);
        $request = $this->getMockBuilder(Request::class)->getMock();
        $request->attributes = $bag;
        $this->event->expects(self::once())
            ->method('isMasterRequest')
            ->willReturn(true);
        $this->event->expects(self::once())
            ->method('getRequest')
            ->willReturn($request);

        $eventSubscriber = new TermsOfServiceSubscriber($this->tokenStorage, $this->router, $this->TOSService, $this->authenticators);
        $eventSubscriber->onKernelRequest($this->event);
    }

    public function testOnKernelRequestAlreadyHasRole(): void
    {
        /**
         * @var UserInterface|MockObject $user
         */
        $user = $this->getMockBuilder(UserInterface::class)->getMock();
        $token = new PostAuthenticationGuardToken($user, 'main', []);
        $token->setAttribute('mp3000mp_roles', [Mp3000mpTOSService::ROLE_TOS_SIGNED]);
        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($token);

        $this->TOSService->expects(self::never())
            ->method('getLastSignedTOS');

        $bag = $this->getMockBuilder(ParameterBag::class)->getMock();
        $bag->expects(self::once())
            ->method('get')
            ->willReturn('home');
        $request = $this->getMockBuilder(Request::class)->getMock();
        $request->attributes = $bag;
        $this->event->expects(self::once())
            ->method('isMasterRequest')
            ->willReturn(true);
        $this->event->expects(self::once())
            ->method('getRequest')
            ->willReturn($request);

        $eventSubscriber = new TermsOfServiceSubscriber($this->tokenStorage, $this->router, $this->TOSService, $this->authenticators);
        $eventSubscriber->onKernelRequest($this->event);
    }

    public function testOnKernelRequestBadAuthenticator(): void
    {
        $user = $this->getMockBuilder(UserInterface::class)->getMock();
        $token = new PostAuthenticationGuardToken($user, 'other', []);
        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($token);

        $this->TOSService->expects(self::never())
            ->method('getLastSignedTOS');

        $bag = $this->getMockBuilder(ParameterBag::class)->getMock();
        $bag->expects(self::once())
            ->method('get')
            ->willReturn('home');
        $request = $this->getMockBuilder(Request::class)->getMock();
        $request->attributes = $bag;
        $this->event->expects(self::once())
            ->method('isMasterRequest')
            ->willReturn(true);
        $this->event->expects(self::once())
            ->method('getRequest')
            ->willReturn($request);

        $eventSubscriber = new TermsOfServiceSubscriber($this->tokenStorage, $this->router, $this->TOSService, $this->authenticators);
        $eventSubscriber->onKernelRequest($this->event);
    }

    public function testOnKernelRequestNotAuthenticated(): void
    {
        $token = new UsernamePasswordToken('test', 'test', 'main');
        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($token);

        $this->TOSService->expects(self::never())
            ->method('getLastSignedTOS');

        $bag = $this->getMockBuilder(ParameterBag::class)->getMock();
        $bag->expects(self::once())
            ->method('get')
            ->willReturn('home');
        $request = $this->getMockBuilder(Request::class)->getMock();
        $request->attributes = $bag;
        $this->event->expects(self::once())
            ->method('isMasterRequest')
            ->willReturn(true);
        $this->event->expects(self::once())
            ->method('getRequest')
            ->willReturn($request);

        $eventSubscriber = new TermsOfServiceSubscriber($this->tokenStorage, $this->router, $this->TOSService, $this->authenticators);
        $eventSubscriber->onKernelRequest($this->event);
    }

    public function testOnKernelRequestAddSignedRole(): void
    {
        $user = $this->getMockBuilder(UserInterface::class)->getMock();
        $token = new PostAuthenticationGuardToken($user, 'main', []);
        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($token);

        $this->TOSService->expects(self::once())
            ->method('getLastSignedTOS')
            ->willReturn(new TermsOfService());
        $this->TOSService->expects(self::once())
            ->method('addTOSSignedRole');

        $session = $this->getMockBuilder(Session::class)->getMock();
        $bag = $this->getMockBuilder(ParameterBag::class)->getMock();
        $bag->expects(self::once())
            ->method('get')
            ->willReturn('home');
        $request = $this->getMockBuilder(Request::class)->getMock();
        $request->expects(self::once())
            ->method('getSession')
            ->willReturn($session);
        $request->attributes = $bag;

        $this->event->expects(self::once())
            ->method('isMasterRequest')
            ->willReturn(true);
        $this->event->expects(self::exactly(2))
            ->method('getRequest')
            ->willReturn($request);

        $eventSubscriber = new TermsOfServiceSubscriber($this->tokenStorage, $this->router, $this->TOSService, $this->authenticators);
        $eventSubscriber->onKernelRequest($this->event);
    }

    public function testOnKernelRequestRedirectToTOS(): void
    {
        $user = $this->getMockBuilder(UserInterface::class)->getMock();
        $token = new PostAuthenticationGuardToken($user, 'main', []);
        $this->tokenStorage->expects(self::once())
            ->method('getToken')
            ->willReturn($token);

        $this->router->expects(self::once())
            ->method('generate')
            ->willReturn('/tos/');

        $this->TOSService->expects(self::once())
            ->method('getLastSignedTOS')
            ->willReturn(null);

        $bag = $this->getMockBuilder(ParameterBag::class)->getMock();
        $bag->expects(self::once())
            ->method('get')
            ->willReturn('home');
        $request = $this->getMockBuilder(Request::class)->getMock();
        $request->attributes = $bag;

        $this->event->expects(self::once())
            ->method('isMasterRequest')
            ->willReturn(true);
        $this->event->expects(self::once())
            ->method('getRequest')
            ->willReturn($request);
        $this->event->expects(self::once())
            ->method('setResponse')
            ->willReturn(null);

        $eventSubscriber = new TermsOfServiceSubscriber($this->tokenStorage, $this->router, $this->TOSService, $this->authenticators);
        $eventSubscriber->onKernelRequest($this->event);
    }
}
