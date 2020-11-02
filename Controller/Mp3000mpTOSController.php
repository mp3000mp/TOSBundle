<?php

namespace Mp3000mp\TOSBundle\Controller;

use Mp3000mp\TOSBundle\Exception\Mp3000mpTOSBundleException;
use Mp3000mp\TOSBundle\Service\Mp3000mpTOSService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Mp3000mpTOSController extends AbstractController
{
    /**
     * @var Mp3000mpTOSService
     */
    private $TOSService;

    public function __construct(Mp3000mpTOSService $TOSService)
    {
        $this->TOSService = $TOSService;
    }

    public function index(): Response
    {
        $lastSigned = $this->TOSService->getLastSignedTOS($this->getUser());

        return $this->render('@Mp3000mpTOS/tos.html.twig', [
            'alreadySigned' => null !== $lastSigned,
        ]);
    }

    /**
     * @return RedirectResponse|Response
     *
     * @throws Mp3000mpTOSBundleException
     */
    public function sign(Request $request)
    {
        if (null === $this->getUser()) {
            throw new Mp3000mpTOSBundleException('Signing terms of service should not be allowed when not fully authenticated.');
        }
        if ('agreed' === $request->get('mp3000mp-tos')) {
            $lastTos = $this->TOSService->getLastTOS();
            if (null === $lastTos) {
                throw new Mp3000mpTOSBundleException('No terms of service found.');
            }
            $lastSigned = $this->TOSService->getLastSignedTOS($this->getUser());
            if ($lastTos === $lastSigned) {
                throw new Mp3000mpTOSBundleException('This user has already signed the last terms of service.');
            }

            $this->TOSService->persistSignature($lastTos, $this->getUser());

            return $this->redirect('/');
        } else {
            return $this->render('@Mp3000mpTOS/tos.html.twig', [
                'error' => true,
            ]);
        }
    }
}
