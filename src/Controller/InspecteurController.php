<?php
namespace App\Controller;
 
use App\Repository\ChantierRepository;
use App\Repository\InspecteurRepository;
use App\Service\GeocodeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
 
#[IsGranted('ROLE_INSPECTEUR')]
class InspecteurController extends AbstractController
// role de l'inspecteur
{
    #[Route('/inspecteur/dashboard', name: 'app_inspecteur_dashboard')]
    public function dashboard(ChantierRepository $chantierRepository, InspecteurRepository $inspecteurRepository): Response
    {
        $user = $this->getUser();
        $inspecteur = $inspecteurRepository->findOneBy(['email' => $user->getEmail()]);
       
        if ($inspecteur) {
            $chantiers = $chantierRepository->findBy(['Inspecteur' => $inspecteur]);
        } else {
            $chantiers = [];
        }
 
        return $this->render('inspecteur/index.html.twig', [
            'user' => $user,
            'chantiers' => $chantiers,
        ]);
    }
 
    #[Route('/inspecteur/chantier/{id}', name: 'app_inspecteur_detail')]
    public function detail(int $id, ChantierRepository $chantierRepository, GeocodeService $geocodeService): Response
    {
        $chantier = $chantierRepository->find($id);
       
        if (!$chantier) {
            throw $this->createNotFoundException('Chantier non trouvé');
        }
 
        // Récupérer l'adresse complète
        $adresse = $chantier->getBien()->getAdresse();
        $ville = $chantier->getBien()->getVille();
        $codePostal = $chantier->getBien()->getCodePostal();
        $fullAddress = "$adresse, $codePostal $ville, France";
 
        // Géocoder l'adresse
        $coordinates = $geocodeService->geocodeAddress($fullAddress);
 
        if (!$coordinates) {
            // Coordonnées par défaut si géocodage échoue
            $coordinates = ['lat' => 48.8566, 'lng' => 2.3522];
        }
 
        $googleMapsApiKey = $this->getParameter('app.google_maps_api_key');
 
        return $this->render('inspecteur/detail.html.twig', [
            'chantier' => $chantier,
            'googleMapsApiKey' => $googleMapsApiKey,
            'coordinates' => $coordinates,
        ]);
    }
}