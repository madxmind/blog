<?php

namespace App\Controller;

use App\Entity\User;
use App\Handler\RegistrationHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{

    /**
     * @Route("/inscription", name="registration")
     * @param  Request $request
     * @return Response
     */
    public function __invoke(Request $request, RegistrationHandler $registrationHandler): Response
    {
        $user = new User();
        if ($registrationHandler->handle($request, $user)) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration.html.twig', [
            'form' => $registrationHandler->createView(),
        ]);
    }
}
