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
        $totalPrice = 0;
        $arrayGraph = [];
        $arrayPrice = [];
        $command = $entityManager->getRepository(Command::class)->findAll();
        for ($i=0; $i < count($command); ++$i) {
            array_push($arrayGraph,['date' => $command[$i]->getDate(), 'priceDate'=> 0]);
            $commandLine = $entityManager->getRepository(CommandLine::class)->find($command[$i]->getId());
            array_push($arrayGraph[''], $commandLine->getProduct()->getPrice() * $commandLine->getQuantity());
        }

        return $this->render('graph_command/index.html.twig', [
            'price' => $arrayPrice,
            'totalPrice' => $totalPrice,
        ]);
    }
}
