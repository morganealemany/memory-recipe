<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\RecipeRepository;
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
}
