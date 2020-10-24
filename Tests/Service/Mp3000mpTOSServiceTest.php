<?php

namespace Mp3000mp\TOSBundle\Tests\Service;

use Doctrine\ORM\EntityManager;
use Mp3000mp\TOSBundle\Entity\TermsOfService;
use Mp3000mp\TOSBundle\Entity\TermsOfServiceRepository;
use Mp3000mp\TOSBundle\Service\Mp3000mpTOSService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;

class Mp3000mpTOSServiceTest extends TestCase
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var UserInterface
     */
    private $user;

    public function setUp(): void
    {
        $rep = $this->createMock(TermsOfServiceRepository::class);
        $rep->expects(self::any())
            ->method('findLast')
            ->willReturn(new TermsOfService());
        $rep->expects(self::any())
            ->method('findLastSignedByUser')
            ->willReturn(new TermsOfService());

        $em = $this->createMock(EntityManager::class);
        $em->expects(self::any())
            ->method('getRepository')
            ->willReturn($rep);
        $em->expects(self::any())
            ->method('persist')
            ->willReturn(true);
        $em->expects(self::any())
            ->method('flush')
            ->willReturn(true);

        $user = $this->createMock(UserInterface::class);

        $this->user = $user;
        $this->em = $em;
    }

    public function testGetLastTOS(): void
    {
        $service = new Mp3000mpTOSService($this->em);
        $tos = $service->getLastTOS();

        self::assertInstanceOf(TermsOfService::class, $tos);
    }

    public function testGetLastSignedTOS(): void
    {
        $service = new Mp3000mpTOSService($this->em);
        $tos = $service->getLastSignedTOS($this->user);

        self::assertInstanceOf(TermsOfService::class, $tos);
    }

    // todo
    /*public function testAddTOSSignedRole()
    {

    }*/

    public function testPersisteSignature(): void
    {
        $service = new Mp3000mpTOSService($this->em);
        $tos = new TermsOfService();
        $toss = $service->persisteSignature($tos, $this->user);

        self::assertEquals($toss->getUser(), $this->user);
        self::assertEquals($toss->getTermsOfService(), $tos);
        self::assertEquals($toss->getSignedAt()->format('Y-m-d H:i:s'), date('Y-m-d H:i:s'));
    }
}
