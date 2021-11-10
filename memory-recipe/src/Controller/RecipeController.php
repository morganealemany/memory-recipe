<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController
{
    /**
     * Method to display the details of a recipe
     * 
     * @Route("/recette/{id}", name="recipe", requirements={"id": "\d+"})
     * 
     * @param int $id The recipe id
     */
    public function show(int $id, RecipeRepository $recipeRepository): Response
    {
        $recipe = $recipeRepository->find($id);
        dump($recipe);
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }
}
