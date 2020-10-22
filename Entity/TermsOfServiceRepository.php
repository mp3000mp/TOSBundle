<?php

namespace Mp3000mp\TOSBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class TermsOfServiceRepository extends EntityRepository
{

    public function findLast(): ?TermsOfService
    {
        return $this->createQueryBuilder('tos')
            ->orderBy('tos.published_at', 'DESC')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findLastSignedByUser(UserInterface $user): bool
    {
        return $this->createQueryBuilder('tos')
            ->innerJoin('tos.terms_of_service_signatures', 'tosSign')
            ->where('tosSign.user = :user')
            ->setParameter('user', $user)
            ->orderBy('tos.published_at', 'DESC')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

}
