<?php

namespace App\Controller;

use App\Entity\Command;
use App\Entity\CommandLine;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/products/{id}', name: 'app_product', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, int $id): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($id);

        return $this->render('product/index.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/products/{id}', name: 'app_product_post')]
    public function add_Command(
        EntityManagerInterface $entityManager,
        Request $request,
        Product $product
    ):Response
    {
        $user = $this->getUser();
        $quantity = $request->request->get('quantity');
        $command = $entityManager->getRepository(Command::class);
        $date = new \DateTimeImmutable('now');
        $currentDate = $date->setTime(0, 0, 0);

        $findCommand = $command->findOneBy([
            'user' => $user,
            'isValid' => true,
            'date' => $currentDate
        ]);

        $commandLine = New CommandLine();

        $commandLine->setProduct($product);
        $commandLine->setQuantity($quantity);

        if ($findCommand){
            $commandLine->setSale($findCommand);
        }else{
            $setCommand = new Command();
            $setCommand->setDate($currentDate);
            $setCommand->setUser($user);
            $setCommand->setIsValid(true);
            $commandLine->setSale($setCommand);
            $entityManager->persist($setCommand);
        }

        $entityManager->persist($commandLine);
        $entityManager->flush();

        return $this->render('home_page/index.html.twig', [
            'product' => $product,
        ]);
    }
}
