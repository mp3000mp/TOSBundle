<?php

namespace Mp3000mp\TOSBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Mp3000mp\TOSBundle\Entity\TermsOfService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

class Mp3000mpTOSService
{

    public const ROLE_TOS_SIGNED = 'MP3000MP_TOS_SIGNED';
    public const ROUTE_TOS = 'mp3000mp_tos';

    /** @var EntityManagerInterface */
    private $em;

    /**
     * TOSService constructor.
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getLastTOS(): TermsOfService
    {
        $repTOS = $this->em->getRepository(TermsOfService::class);

        return $repTOS->findLast();
    }

    public function hasSignedLastTOS(UserInterface $user): bool
    {
        $repTOS = $this->em->getRepository(TermsOfService::class);

        $lastSignedTOS = $repTOS->findLastSignedByUser($user);

        return null !== $lastSignedTOS;
    }

    public function addTOSSignedRole(TokenStorageInterface $tokenStorage, SessionInterface $session): void
    {
        /** @var PostAuthenticationGuardToken $currentToken */
        $currentToken = $tokenStorage->getToken();
        $roles = array_merge($currentToken->getRoleNames(), [self::ROLE_TOS_SIGNED]);
        $newToken = new PostAuthenticationGuardToken($currentToken->getUser(), $currentToken->getProviderKey(), $roles);
        $tokenStorage->setToken($newToken);
        $session->set('_security_'.$currentToken->getProviderKey(), serialize($newToken));
    }

}
