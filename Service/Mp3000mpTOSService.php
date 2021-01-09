<?php

namespace Mp3000mp\TOSBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Mp3000mp\TOSBundle\Entity\TermsOfService;
use Mp3000mp\TOSBundle\Entity\TermsOfServiceRepository;
use Mp3000mp\TOSBundle\Entity\TermsOfServiceSignature;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

class Mp3000mpTOSService
{
    /**
     * @var string
     */
    public const ROLE_TOS_SIGNED = 'MP3000MP_TOS_SIGNED';

    /**
     * @var array
     */
    public const ROUTES_TOS = ['mp3000mp_tos.show_last', 'mp3000mp_tos.post'];

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * TOSService constructor.
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getLastTOS(): ?TermsOfService
    {
        /**
         * @var TermsOfServiceRepository $repTOS
         */
        $repTOS = $this->em->getRepository(TermsOfService::class);

        return $repTOS->findLast();
    }

    public function getLastSignedTOS(?UserInterface $user): ?TermsOfService
    {
        if (null === $user) {
            return null;
        }

        /**
         * @var TermsOfServiceRepository $repTOS
         */
        $repTOS = $this->em->getRepository(TermsOfService::class);

        return $repTOS->findLastSignedByUser($user);
    }

    public function addTOSSignedRole(TokenStorageInterface $tokenStorage, SessionInterface $session): void
    {
        /**
         * @var PostAuthenticationGuardToken $currentToken
         */
        $currentToken = $tokenStorage->getToken();
        $arr = $currentToken->getAttributes()['mp3000mp_roles'] ?? [];
        $arr = array_merge($arr, [self::ROLE_TOS_SIGNED]);
        $currentToken->setAttribute('mp3000mp_roles', $arr);
        $tokenStorage->setToken($currentToken);
        $session->set('_security_'.$currentToken->getProviderKey(), serialize($currentToken));
    }

    public function persistSignature(TermsOfService $termsOfService, UserInterface $user): TermsOfServiceSignature
    {
        $tosSigned = new TermsOfServiceSignature();
        $tosSigned->setSignedAt(new \DateTime());
        $tosSigned->setUser($user);
        $tosSigned->setTermsOfService($termsOfService);

        $this->em->persist($tosSigned);
        $this->em->flush();

        return $tosSigned;
    }
}
