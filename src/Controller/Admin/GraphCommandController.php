<?php

namespace App\Controller\Admin;

use App\Entity\Command;
use App\Entity\CommandLine;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\NotSupported;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GraphCommandController extends AbstractController
{
    #[Route('/admin/graph', name: 'graph_command')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $arrayGraph = [];
        $arrayPrice = [];
        $commands = $entityManager->getRepository(Command::class)->findBy([], ["date" => 'ASC']);

        foreach ($commands as $command) {

            $formattedDate = $command->getDate()->format('Y-m-d');
            $arrayGraph[] = $formattedDate;

            $commandLines = $entityManager->getRepository(CommandLine::class)->findBy(['sale' => $command]);
            $totalPrice = 0;

            foreach ($commandLines as $commandLine) {
                $totalPrice += $commandLine->getProduct()->getPrice() * $commandLine->getQuantity();
            }

            $arrayPrice[] = $totalPrice;
        }

        return $this->render('graph_command/index.html.twig', [
            'price' => $arrayPrice,
            'date' => $arrayGraph,
        ]);
    }
}
