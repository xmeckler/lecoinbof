<?php

namespace App\Controller;

use App\Form\SearchAdvertisementType;
use App\Repository\AdvertisementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SearchController extends Controller
{
    /**
     * @Route("/search", name="search")
     */
    public function index(Request $request, AdvertisementRepository $advertisementRepository)
    {
        $form = $this->createForm(SearchAdvertisementType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $searchInput = $data['search'];
            // recherche par LIKE
            $advertisements = $advertisementRepository->searchAdvertisements($searchInput);
            // si pas de resultat, recherche floue (metaphone + levenstein)
            if (empty($advertisements)) {
                $allAdvertisements = $advertisementRepository->findAll();
                foreach ($allAdvertisements as $advertisement) {
                    $titleWords = explode(' ', $advertisement->getTitle());
                    foreach ($titleWords as $word) {
                        if (
                            metaphone($word) === metaphone($searchInput)
                            ||
                            levenshtein($word, $searchInput) <= 3
                        ) {
                            $proposal = $word;
                            break;
                        }
                    }
                }
            }
        }

        return $this->render('search/index.html.twig', [
            'form'    => 'SearchController',
            'proposal' => $proposal ?? '',
            'advertisements' => $advertisements ?? [],
        ]);
    }
}
