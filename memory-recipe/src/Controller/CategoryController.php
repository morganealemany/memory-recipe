<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categorie/{id}", name="category_show", requirements={"id": "\d+"})
     * 
     * @param int $id The category id
     * @return Response
     */
    public function show(int $id, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->find($id);
        
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }
}
