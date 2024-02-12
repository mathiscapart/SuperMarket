<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Command;
use App\Entity\CommandLine;
use App\Entity\Product;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(ProductCrudController::class)->generateUrl();

        return $this->redirect($url);

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('AdminMarket');
    }

    public function configureMenuItems(): iterable
    {
        $roles = $this->getUser()->getRoles();

        for ($i = 0; count($roles) > $i; $i++) {
            if ($roles[$i] == "ROLE_ADMIN") {
                yield MenuItem::linktoRoute('Back to the website', 'fas fa-home', 'app_home_page');
                yield MenuItem::linkToCrud('User', 'fas fa-user', User::class);
                yield MenuItem::linkToCrud('Product', 'fas fa-list', Product::class);
                yield MenuItem::linkToCrud('Category', "fa-solid fa-folder-open", Category::class);
                yield MenuItem::linkToCrud('Command', 'fas fa-cube', Command::class);
                yield MenuItem::linkToRoute('Graph Command', 'fa-solid fa-chart-simple', "graph_command");
            }
            if ($roles[$i] == "ROLE_CASHIER") {
                yield MenuItem::linktoRoute('Back to the website', 'fas fa-home', 'app_home_page');
                yield MenuItem::linkToCrud('Product', 'fas fa-bars', Product::class);
                yield MenuItem::linkToCrud('Command', 'fas fa-cube', Command::class);
            }
            if ($roles[$i] == "ROLE_ACCOUNTANT") {
                yield MenuItem::linktoRoute('Back to the website', 'fas fa-home', 'app_home_page');
                yield MenuItem::linkToRoute('Graph Command', 'fas fa-cube', "graph_command");
            }
        }
        yield MenuItem::linkToLogout('Logout', 'fa fa-gear');
    }
}
