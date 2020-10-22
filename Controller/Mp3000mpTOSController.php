<?php

namespace Mp3000mp\TOSBundle\Controller;

use Mp3000mp\TOSBundle\Entity\TermsOfService;
use Mp3000mp\TOSBundle\Entity\TermsOfServiceSignature;
use Mp3000mp\TOSBundle\Exception\Mp3000mpTOSBundleException;
use Mp3000mp\TOSBundle\Service\Mp3000mpTOSService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class Mp3000mpTOSController extends AbstractController
{
    public function index()
    {
        return $this->render('@Mp3000mpTOS/tos.html.twig', [
        ]);
    }

    public function sign(Request $request, Mp3000mpTOSService $TOSService)
    {
        if($this->getUser() === null){
            throw new Mp3000mpTOSBundleException('Signing terms of service should not be allowed when not fully authenticated.');
        }
        if($request->get('mp3000mp-tos') === 'agreed'){

            $lastTos = $TOSService->getLastTOS();
            if($lastTos === null){
                throw new Mp3000mpTOSBundleException('No terms of service found.');
            }
            $lastSigned = $TOSService->hasSignedLastTOS($this->getUser());
            if($lastTos === $lastSigned){
                throw new Mp3000mpTOSBundleException('This user has already signed the last terms of service.');
            }

            $tosSigned = new TermsOfServiceSignature();
            $tosSigned->setSignedAt(new \DateTime());
            $tosSigned->setUser($this->getUser());
            $tosSigned->setTermsOfService($lastTos);

            $this->getDoctrine()->persist($tosSigned);
            $this->getDoctrine()->flush();

            $TOSService->addTOSSignedRole();

            $this->redirect('/');

        }else{
            return $this->render('@Mp3000mpTOS/tos.html.twig', [
                'error' => true,
            ]);
        }
    }
}
