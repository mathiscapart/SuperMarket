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
    public function index(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($id);

        if ($product == null) {
            throw $this->createNotFoundException('The product does not exist');
        } elseif ($product->isVisible() == 0) {
            throw $this->createNotFoundException('The product does not exist');
        }

        if ($request->request->get('stock') == null) {
            $stock = true;
        } else {
            $stock = $request->request->get('stock');
        }

        if ($request->query->get('popup') == null) {
            $popup = false;
        } else {
            $popup = $request->query->get('popup');
        }

        return $this->render('product/index.html.twig', [
            'product' => $product,
            'stock' => $stock,
            'popup' => $popup
        ]);
    }

    #[Route('/products/{id}', name: 'app_product_post')]
    public function add_Command(
        EntityManagerInterface $entityManager,
        Request                $request,
        Product                $product,
        int                    $id
    ): Response
    {
        $user = $this->getUser();
        $quantity = $request->request->get('quantity');
        $command = $entityManager->getRepository(Command::class);
        $date = new \DateTimeImmutable('now');
        $currentDate = $date->setTime(0, 0, 0);

        $findCommand = $command->findOneBy([
            'user' => $user,
            'isValid' => false,
        ]);

        $commandLine = new CommandLine();

        $commandLine->setProduct($product);
        $stockProduct = $product->getStock();
        $newStock = $stockProduct - $quantity;

        if ($stockProduct == 0) {
            return $this->redirectToRoute('app_product', [
                'id' => $id,
                'stock' => false
            ]);
        } elseif ($newStock < 0) {
            return $this->redirectToRoute('app_product', [
                'id' => $id,
                'stock' => false
            ]);
        }

        $commandLine->setQuantity($quantity);
        $product->setStock($newStock);
        $entityManager->persist($product);

        if ($findCommand) {
            $commandLine->setSale($findCommand);
        } else {
            $setCommand = new Command();
            $setCommand->setDate($currentDate);
            $setCommand->setUser($user);
            $setCommand->setIsValid(false);
            $commandLine->setSale($setCommand);
            $entityManager->persist($setCommand);
        }

        $entityManager->persist($commandLine);
        $entityManager->flush();

        return $this->redirectToRoute('app_product', ['id' => $id]);
    }


}
