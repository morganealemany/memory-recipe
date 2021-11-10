<?php
namespace App\Service;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;

class RecipeService
{
    /**
     * Method to select a random index in the database
     *
     * @param  $recipeRepository
     * @return Recipe
     */
    public function randomRecipe(RecipeRepository $recipeRepository) : Recipe
    {
        $recipes = $recipeRepository->findAll();

        $randomRecipeIndex = array_rand($recipes);

        return $recipes[$randomRecipeIndex];
    }
}