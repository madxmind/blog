<?php

namespace App\Handler;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationHandler extends AbstractHandler
{
    private EntityManagerInterface $entityManager;
    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }


    protected function getFormType(): string
    {
        return UserType::class;
    }

    protected function process($data): void
    {
        $data->setPassword($this->userPasswordEncoder->encodePassword($data, $this->form->get('plainPassword')->getData()));

        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }
}
