<?php

namespace App\Controller;

use App\Entity\Command;
use App\Entity\CommandLine;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $command = $entityManager->getRepository(Command::class);
        $commandLine = $entityManager->getRepository(CommandLine::class);

        $commandUser = $command->findBy([
            'user' => $user,
            'isValid' => true
        ]);

        $commandLines = $commandLine->findBy(['sale' => $commandUser]);

        return $this->render('profil/index.html.twig', [
            'command' => $commandUser,
            'commandLines' => $commandLines
        ]);
    }
}
