<?php

namespace App\Controller;

use App\Entity\OffresEmploi;
use App\Repository\ParametresRepository;
use App\Repository\OffresEmploiRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/offres-emploi')]
final class OffresController extends AbstractController
{
    #[Route('/', name: 'app_offres')]
    public function index(Request $request, OffresEmploiRepository $offresEmploiRepository, ParametresRepository $parametresRepository, PaginatorInterface $paginator): Response
    {
        $search = [
            'keywords' => $request->request->get('keywords', $request->query->get('keywords', '')),
            'lieu' => $request->request->get('lieu', $request->query->get('lieu', '')),
        ];

        $filters = [
            'metier' => $request->request->get('metier', $request->query->get('metier', '')),
            'experience' => $request->request->get('experience', $request->query->get('experience', '')),
            'niveau_poste' => $request->request->get('niveau_poste', $request->query->get('niveau_poste', '')),
            'secteur' => $request->request->get('secteur', $request->query->get('secteur', '')),
            'niveau_formation' => $request->request->get('niveau_formation', $request->query->get('niveau_formation', '')),
        ];

        $query = $offresEmploiRepository->getFilteredQuery($search, $filters);
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            6
        );

        if ($request->headers->get('Turbo-Frame') === 'offres_results') {
            return $this->render('front/offres-emploi/_offres.html.twig', [
                'pagination' => $pagination,
                'search' => $search,
                'filters' => $filters,
            ]);
        }
        return $this->render('front/offres-emploi/index.html.twig', [
            'offres' => $offresEmploiRepository->findAll(),
            'metiers' => $parametresRepository->findByType('metiers'),
            'secteurs' => $parametresRepository->findByType('secteurs'),
            'pagination' => $pagination,
            'search' => $search,
            'filters' => $filters,
        ]);
    }

    #[Route('/details/{offre}', name: 'details_offre')]
    public function details(OffresEmploi $offre, OffresEmploiRepository $offresEmploiRepository): Response
    {
        return $this->render('front/offres-emploi/details.html.twig', [
            'offre' => $offre,
        ]);
    }
}
