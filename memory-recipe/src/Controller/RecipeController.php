<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        if (!empty($_POST['commentaire'])) {
            $newComment = filter_input(INPUT_POST, 'commentaire', FILTER_SANITIZE_STRING);
            $comment = new Comment();
            $em = $this->getDoctrine()->getManager();
            $comment->setUser($this->getUser());
            $comment->setText($newComment);
            $comment->setRecipe($recipe);
            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', 'Votre commentaire a bien été enregistré');
        }
     
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    /**
     * Method to create a new recipe
     * 
     * @Route("/recette/creer", name="recipe_create")
     *
     * @return void
     */
    public function create(Request $request)
    {
        $recipe = new Recipe();

        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        $recipe->setUser($this->getUser());

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($recipe);
            foreach ($form->getData()->getIngredient() as $ingredient) {
                $ingredient->setRecipe($recipe);
                $em->persist($ingredient);
            }
            $em->flush();

            $this->addFlash('success', 'La recette ' . $recipe->getName() . ' a bien été créée.');
            
            return $this->redirectToRoute('home');
        }
        return $this->render('recipe/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
