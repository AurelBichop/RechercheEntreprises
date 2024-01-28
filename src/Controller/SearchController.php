<?php

namespace App\Controller;

use App\Form\SocietyType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SearchController extends AbstractController
{
    #[Route('/', name: 'app_search')]
    public function index(Request $request, HttpClientInterface $httpClient): Response
    {
        $results = null;

        $form = $this->createForm(SocietyType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $request = $httpClient->request(
                'GET',
                'https://recherche-entreprises.api.gouv.fr/search?q='.$form->getData()['field_name'].'&activitePrincipale=62.01Z',
                [
                    'headers' => [
                        'Accept' => 'application/json',
                    ],
                ]
            );

            $results = $request->toArray()['results'];
        }

        return $this->render('search/index.html.twig', [
            'form' => $form,
            'results' => $results,
        ]);
    }
}