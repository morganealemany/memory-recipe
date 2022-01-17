<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\RecipeRepository;
use App\Service\RecipeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        $lastRecipes =$recipeRepository->findBy(array(),array('id'=>'DESC'),3,0);

        // DERNIERS COMMENTAIRES
        $lastComments = $commentRepository->findBy(array(), array('id'=>'DESC'),5,0);

        return $this->render('home/index.html.twig', [
            'randomRecipe' => $randomRecipe,
            'lastRecipes' => $lastRecipes,
            'lastComments' => $lastComments,
        ]);
    }


    /**
     * Method to display contact form and submitted it
     * 
     * @Route("/contact", name="contact")
     *
     * @return void
     */
    public function contact(Request $request) {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {   
            $to = 'm.plancheron@gmail.com';
            $subject = $request->request->get('subject');
            $message = $request->request->get('message');
            $from = 'petitemo_1011@hotmail.fr';
            // dd($subject, $message, $sender->getEmail());
            $retour = mail($to, $subject, $message);
            dd($retour);
            if ($retour) {
                $this->addFlash('success', 'Votre message a bien été envoyé');
            }
            else {
                $this->addFlash('warning', 'Votre message n\'a pas été envoyé, veuillez réessayer plus tard.');
            }
            // TODO essayer plutot avec symfony mailer peut-être car adresse smtp avec free en 4G???

        }
        return $this->render('contact.html.twig');
    }
}
