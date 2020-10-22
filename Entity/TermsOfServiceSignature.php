<?php

declare(strict_types=1);

namespace Mp3000mp\TOSBundle\Entity;

use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;

class TermsOfServiceSignature
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @var TermsOfService
     */
    private $terms_of_service;

    /**
     * @var DateTime
     */
    private $signed_at;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): void
    {
        $this->user = $user;
    }

    /**
     * @return TermsOfService
     */
    public function getTermsOfService()
    {
        return $this->terms_of_service;
    }

    /**
     * @param TermsOfService $terms_of_service
     */
    public function setTermsOfService($terms_of_service): void
    {
        $this->terms_of_service = $terms_of_service;
    }

    /**
     * @return DateTime
     */
    public function getSignedAt()
    {
        return $this->signed_at;
    }

    /**
     * @param DateTime $signed_at
     */
    public function setSignedAt($signed_at): void
    {
        $this->signed_at = $signed_at;
    }
}
