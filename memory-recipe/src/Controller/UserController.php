<?php

namespace App\Controller;

use App\Form\RecipeType;
use App\Form\UserType;
use App\Repository\RecipeRepository;
use App\Service\ImageUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/profil", name="profile_")
 */
class UserController extends AbstractController
{
    /**
     * Method to edit profile informations
     * 
     * @Route("/infos", name="infos")
     */
    public function profileInformations(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();
        dump($user);
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
                );
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Vos informations ont bien été modifiées');
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/infos.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * Method to display all the user connected recipes
     * 
     * @Route("/recettes", name="recipes")
     */
    public function profileRecipes() 
    {
        $user = $this->getUser();

        return $this->render('user/recipes.html.twig', [
            'user' => $user,
        ]);
    } 

    /**
     * Method to display all the user connected comments
     * 
     * @Route("/commentaires", name="comments")
     */
    public function profileComments()
    {
        $user = $this->getUser();

        return $this->render('user/comments.html.twig', [
            'user' => $user,
        ]);

    }

    /**
     * Method to edit a recipe
     * 
     * @Route("/recette/{id}/modifier", name="recipe_edit")
     *
     * @param integer $id The id of the recipe to edit
     * @return void
     */
    public function edit(int $id, Request $request, RecipeRepository $recipeRepository, ImageUploader $imageUploader) {

        $recipe = $recipeRepository->find($id);
        // dd($recipe);
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        // $recipe->setUser($this->getUser());

        if ($form->isSubmitted() && $form->isValid()) {

            // Upload the file with ImageUploader
            $newFilename = $imageUploader->upload($form, 'picture');

            // Update the image porperty
            if ($newFilename) {
                $recipe->setPicture($newFilename);
            }
            $em = $this->getDoctrine()->getManager();
            // $em->persist($recipe);
            foreach ($form->getData()->getIngredient() as $ingredient) {
                $ingredient->setRecipe($recipe);
                $em->persist($ingredient);
            }
            $em->flush();

            $this->addFlash('success', 'La recette ' . $recipe->getName() . ' a bien été modifiée.');
            
            return $this->redirectToRoute('home');
        }
        return $this->render('user/recipe/edit.html.twig', [
            'form' => $form->createView(),
            'recipe' => $recipe,
        ]);

    }
}
