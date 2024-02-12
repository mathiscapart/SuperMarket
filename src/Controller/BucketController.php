<?php

namespace App\Controller;

use App\Entity\Command;
use App\Entity\CommandLine;
use App\Entity\Product;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BucketController extends AbstractController
{
    #[Route('/bucket', name: 'app_bucket')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $command = $entityManager->getRepository(Command::class);
        $commandLine = $entityManager->getRepository(CommandLine::class);

        $commandUser = $command->findOneBy([
            'user' => $user,
            'isValid' => false
        ]);

        $commandLines = $commandLine->findBy(['sale' => $commandUser]);

        return $this->render('bucket/index.html.twig', [
            'command' => $commandUser,
            'commandLines' => $commandLines
        ]);
    }

    #[\Symfony\Component\Routing\Annotation\Route('/bucket/{command}', name: 'app_bucket_post')]
    public function post_Bucket(
        EntityManagerInterface $entityManager,
        Command                $command,
    ): Response
    {
        $command->setIsValid(true);
        $entityManager->persist($command);
        $entityManager->flush();

        return $this->redirectToRoute('app_bucket');
    }

    #[\Symfony\Component\Routing\Annotation\Route('/bucket/delete', name: 'app_bucket_delete')]
    public function delete_CommandLine(
        EntityManagerInterface $entityManager,
        CommandLine            $commandLine
    ): Response
    {
        $entityManager->remove($commandLine);
        $entityManager->flush();

        return $this->redirectToRoute('app_bucket');
    }


}
