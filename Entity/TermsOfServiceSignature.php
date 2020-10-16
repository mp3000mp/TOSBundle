<?php

declare(strict_types=1);

namespace mp3000mp\TOSBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 */
class TermsOfServiceSignature
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var UserInterface
     * @ORM\ManyToOne(targetEntity="Symfony\Component\Security\Core\User\UserInterface")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var TermsOfService
     * @ORM\ManyToOne(targetEntity="mp3000mp\TOSBundle\Entity\TermsOfService", inversedBy="terms_of_service_signatures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $terms_of_service;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
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

    /**
     * @return UserInterface
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }

    /**
     * @param UserInterface $user
     */
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
