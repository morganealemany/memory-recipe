<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/recherche", name="search")
     */
    public function index(Request $request, RecipeRepository $recipeRepository): Response
    {
        $allRecipes = $recipeRepository->findAll();
        
        $query = $request->query->get('search');
        dump($query);
        $results = $recipeRepository->findRecipesByName($query);
        dump($results);
        
        if (empty($results)) {
            $this->addFlash('warning', 'Aucuns résultats pour votre recherche : "' . $query . '"');
        }
        return $this->render('search/index.html.twig', [
            'results' => $results,
            'query' => $query,
        ]);
    }
}
