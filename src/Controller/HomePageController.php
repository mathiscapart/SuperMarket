<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\NotSupported;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_home_page')]
    public function index()
    {
        return $this->render('home_page/index.html.twig');
    }


    #[Route('/category/{id}', name: 'app_category_page')]
    public function categoryShow(EntityManagerInterface $entityManager ,int $id): Response
    {
        $category = $entityManager->getRepository(Category::class)->find($id);

        $products = $entityManager->getRepository(Product::class)->findBy([
            'category' => $category
        ]);

        return $this->render('home_page/categoryShow.html.twig', [
            'category' => $category,
            'products' => $products
        ]);

    }

    #[Route('/category', name: 'app_category_all')]
    public function categoryAllShow(EntityManagerInterface $entityManager): Response
    {
        $category = $entityManager->getRepository(Category::class)->findAll();

        return $this->render('home_page/categoryAllShow.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/products', name: 'app_products_all')]
    public function productsAllShow(EntityManagerInterface $entityManager): Response
    {
        $products = $entityManager->getRepository(Product::class)->findAll();


        return $this->render('home_page/productsAllShow.html.twig', [
            'products' => $products,
        ]);
    }


}
