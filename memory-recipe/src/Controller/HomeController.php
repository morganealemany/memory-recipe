<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\RecipeRepository;
use App\Service\RecipeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/accueil", name="home")
     */
    public function index(CommentRepository $commentRepository, RecipeRepository $recipeRepository, RecipeService $recipeService): Response
    {
        $recipes = $recipeRepository->findAll();

        // RECETTE ROULETTE
        // Use the custom service RecipeService to select random recipe
        $randomRecipe = $recipeService->randomRecipe($recipeRepository);
        // dump($randomRecipe);

        // NOUVELLES RECETTES
        $lastRecipes =$recipeRepository->findBy(array(),array('id'=>'DESC'),5,0);

        // DERNIERS COMMENTAIRES
        $lastComments = $commentRepository->findBy(array(), array('id'=>'DESC'),5,0);

        return $this->render('home/index.html.twig', [
            'randomRecipe' => $randomRecipe,
            'lastRecipes' => $lastRecipes,
            'lastComments' => $lastComments,
        ]);
    }
}
