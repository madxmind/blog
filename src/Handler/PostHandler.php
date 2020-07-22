<?php

namespace App\Handler;

use App\Form\PostType;
use App\Service\UploaderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;
use Symfony\Component\Security\Core\Security;

class PostHandler extends AbstractHandler
{
    private EntityManagerInterface $entityManager;
    private UploaderInterface $uploader;
    private Security $security;


    public function __construct(EntityManagerInterface $entityManager, UploaderInterface $uploader, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->uploader = $uploader;
        $this->security = $security;
    }


    protected function getFormType(): string
    {
        return PostType::class;
    }

    protected function process($data): void
    {
        $file = $this->form->get('file')->getData();
        if ($file !== null) {
            $data->setImage($this->uploader->upload($file));
        }

        if ($this->entityManager->getUnitOfWork()->getEntityState($data) === UnitOfWork::STATE_NEW) {
            $data->setUser($this->security->getUser());
            $this->entityManager->persist($data);
        }
        $this->entityManager->flush();
    }
}
