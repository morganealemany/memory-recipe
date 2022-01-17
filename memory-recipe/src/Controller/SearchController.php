<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/recherche", name="search_")
 */
class SearchController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request, RecipeRepository $recipeRepository): Response
    {       
        $query = $request->query->get('search');

        $results = $recipeRepository->findRecipesByName($query);

        if (empty($results)) {
            $this->addFlash('warning', 'Aucuns résultats pour votre recherche : "' . $query . '"');
        }

        return $this->render('search/index.html.twig', [
            'results' => $results,
            'query' => $query,
        ]);
    }
}
