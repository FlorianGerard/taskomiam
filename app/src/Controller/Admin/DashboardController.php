<?php

namespace App\Controller\Admin;

use App\Controller\Admin\IngredientCrudController;
use App\Controller\Admin\RecipeIngredientCrudController;
use App\Controller\Admin\CategoryCrudController;
use App\Controller\Admin\KeywordsCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Theme;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use App\Controller\Admin\UserCrudController;
use App\Controller\Admin\RecipeCrudController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(
    routePath: '/admin/{_locale}',
    routeName: 'admin',
    routeOptions: [
        'requirements' => ['_locale' => 'fr|en'],
        'defaults' => ['_locale' => 'fr'],
    ]
)]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // return $this->redirectToRoute('admin_user_index');

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirectToRoute('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // Login


        return $this->render('admin/admin-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<h1>Taskomiam</h1>')
            ->setDefaultColorScheme('dark')
            ->setTheme(Theme::new())
            ->setTranslationDomain('admin')
            ->setLocales(['fr', 'en']);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('dashboard', 'fa fa-home');

        yield MenuItem::section('Users');
        yield MenuItem::linkTo(UserCrudController::class, 'Utilisateurs', 'fa fa-user');
        yield MenuItem::section('Recipes');
        yield MenuItem::linkTo(RecipeCrudController::class, 'Recettes', 'fa fa-utensils');
        yield MenuItem::linkTo(IngredientCrudController::class, 'Ingrédients', 'fa fa-carrot');
        yield MenuItem::linkTo(CategoryCrudController::class, 'Catégories', 'fa fa-folder');
        yield MenuItem::submenu('Paramètres (Recettes et listes)')->setSubItems([
            MenuItem::linkTo(KeywordsCrudController::class, 'Mots-clés', 'fa fa-key'),
        ]);
    }
}
