<?php

declare(strict_types=1);

namespace Mp3000mp\TOSBundle\Entity;

use DateTime;

class TermsOfService
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var DateTime
     */
    private $published_at;

    /**
     * @var TermsOfServiceSignature
     */
    private $terms_of_service_signatures;

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
     * @return DateTime
     */
    public function getPublishedAt()
    {
        return $this->published_at;
    }

    /**
     * @param DateTime $published_at
     */
    public function setPublishedAt($published_at): void
    {
        $this->published_at = $published_at;
    }

    /**
     * @return TermsOfServiceSignature
     */
    public function getTermsOfServiceSignatures()
    {
        return $this->terms_of_service_signatures;
    }

    /**
     * @param TermsOfServiceSignature $terms_of_service_signatures
     */
    public function setTermsOfServiceSignatures($terms_of_service_signatures): void
    {
        $this->terms_of_service_signatures = $terms_of_service_signatures;
    }
}
