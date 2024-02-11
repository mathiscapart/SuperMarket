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
        $command = $entityManager->getRepository(Command::class)->findby([], ["date" => 'ASC']);
        for ($i = 0; $i < count($command); ++$i) {
            array_push($arrayGraph, ['date' => $command[$i]->getDate()]);
            $commandLine = $entityManager->getRepository(CommandLine::class)->findBy(['sale' => $command[$i]]);
            for ($y = 0; $y < count($commandLine); ++$y) {
                array_push($arrayPrice, $commandLine[$y]->getProduct()->getPrice() * $commandLine[$y]->getQuantity());
            }
        }


        return $this->render('graph_command/index.html.twig', [
            'price' => $arrayPrice,
            'date' => $arrayGraph,
        ]);
    }
}
