<?php
namespace App\Controller;

use App\Repository\BienRepository;
use App\Repository\ChantierRepository;
use App\Repository\DevisEntrepreneurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class ProprietaireController extends AbstractController
{
    /**
     * Page 1 : Dashboard - Liste des chantiers en cours
     */
    #[Route('/proprietaire/dashboard', name: 'app_proprietaire_dashboard')]
    public function index(BienRepository $bienRepo, ChantierRepository $chantierRepo): Response
    {
        $user = $this->getUser();
        // Récupère les biens de l'utilisateur
        $biens = $bienRepo->findBy(['utilisateur' => $user]);
        
        // Récupère tous les chantiers liés à ces biens
        $chantiers = $chantierRepo->findBy(['bien' => $biens]);

        return $this->render('proprietaire/index.html.twig', [
            'chantiers' => $chantiers,
        ]);
    }

    /**
     * Page 2 : Comparatif - Tous les devis reçus triés par chantier
     */
    #[Route('/proprietaire/devis-comparatif', name: 'app_proprietaire_comparatif')]
    public function comparatif(BienRepository $bienRepo, ChantierRepository $chantierRepo, DevisEntrepreneurRepository $devisRepo): Response
    {
        $user = $this->getUser();
        $biens = $bienRepo->findBy(['utilisateur' => $user]);
        $chantiers = $chantierRepo->findBy(['bien' => $biens]);

        // On récupère tous les devis pour ces chantiers
        $devisRecus = $devisRepo->findBy(['chantier' => $chantiers]);

        return $this->render('proprietaire/comparatif.html.twig', [
            'chantiers' => $chantiers,
            'devis_recus' => $devisRecus,
        ]);
    }

    /**
     * Action : Validation d'un devis et annulation des autres
     */
    #[Route('/proprietaire/devis/{id}/valider', name: 'app_proprietaire_devis_valider', methods: ['POST'])]
    public function validerDevis(int $id, DevisEntrepreneurRepository $devisRepo, EntityManagerInterface $em): Response
    {
        $devisSelectionne = $devisRepo->find($id);
        if (!$devisSelectionne) {
            throw $this->createNotFoundException("Devis introuvable.");
        }

        $chantier = $devisSelectionne->getChantier();

        // 1. On refuse TOUS les devis de CE chantier
        $tousLesDevisDuChantier = $devisRepo->findBy(['chantier' => $chantier]);
        foreach ($tousLesDevisDuChantier as $devis) {
            $devis->setStatut('Annulé');
            $chantier = $devis->getChantier();
            if ($chantier) {
                $chantier->setStatut('Annulé');
            }
        }

        // 2. On passe le sélectionné à "Accepté"
        $devisSelectionne->setStatut('Accepté');
        $chantierSelectionne = $devisSelectionne->getChantier();
        if ($chantier) {
            $chantier->setStatut('En cours...');
        }

        // 3. Mise à jour du chantier
        $chantier->setStatut('En cours - Entrepreneur validé');
        $chantier->setDateValidation(new \DateTime());

        $em->flush();

        $this->addFlash('success', 'Devis validé ! Les autres offres pour ce chantier ont été annulées.');
        return $this->redirectToRoute('app_proprietaire_comparatif');
    }
}