<?php

namespace App\Controller\ModuleGestionEntreprises\Typesense;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ACSEO\TypesenseBundle\Finder\TypesenseQuery;

class SearchController extends AbstractController
{
    public function __construct(public $entreprisesFinder){
    }

    #[Route('/search', name: 'app_search')]
    public function index(): Response
    {
        $query = new TypesenseQuery('Point de vente', 'pointVentes');

        // Get Doctrine Hydrated objects
        $results = $this->entreprisesFinder->rawQuery($query)->getResults();

        dump($results);
        
        die;
        return $this->render('search/index.html.twig', [
            'results' => $results,
        ]);
    }
}
