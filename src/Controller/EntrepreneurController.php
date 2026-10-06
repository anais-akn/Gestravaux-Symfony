<?php

namespace App\Controller;

use App\Repository\EntrepreneurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\DevisType;
use App\Entity\DevisEntrepreneur;
use App\Entity\DevisEntrepreneurPrestataire;
use App\Repository\PrestataireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\DevisEntrepreneurRepository;
use App\Repository\CategorieRepository; 
use App\Entity\Categorie; 
use App\Entity\PrestataireEntrepreneur;


#[IsGranted('ROLE_ENTREPRENEUR')]
class EntrepreneurController extends AbstractController
{


#[Route('/entrepreneur/dashboard', name: 'app_entrepreneur_dashboard')]
    public function index(
        EntrepreneurRepository $entrepreneurRepo, 
        DevisEntrepreneurRepository $devisEntRepo,
    ): Response {
        $user = $this->getUser();
        $entrepreneur = $entrepreneurRepo->findOneBy(['email' => $user->getEmail()]);

        if (!$entrepreneur) {
            throw $this->createNotFoundException("Profil entrepreneur introuvable.");
        }

        $devisEnvoyes = $devisEntRepo->findBy(['entrepreneur' => $entrepreneur]);
        $chantiersRepondusIds = [];
        foreach ($devisEnvoyes as $devis) {
            $chantiersRepondusIds[] = $devis->getChantier()->getId();
        }

        $devisTypesDisponibles = [];
        foreach ($entrepreneur->getDevisTypes() as $devisType) {
            if (!in_array($devisType->getChantier()->getId(), $chantiersRepondusIds)) {
                $devisTypesDisponibles[] = $devisType;
            }
        }

        return $this->render('entrepreneur/index.html.twig', [
            'entrepreneur' => $entrepreneur,
            'devis_types' => $devisTypesDisponibles,
            'mes_devis_envoyes' => $devisEnvoyes, 
        ]);
    }
    /**
     * Page de formulaire pour compléter le devis
     */
    #[Route('/devis/completer/{id}', name: 'app_entrepreneur_devis_completer', methods: ['GET'])]
    public function completer(DevisType $devisType, EntrepreneurRepository $entrepreneurRepo): Response
    {
        $userEmail = $this->getUser()->getUserIdentifier();
        
        $entrepreneur = $entrepreneurRepo->findOneBy(['email' => $userEmail]);

        if (!$entrepreneur) {
            throw $this->createNotFoundException("Profil entrepreneur introuvable pour l'email " . $userEmail);
        }

        return $this->render('entrepreneur/modifDevis.html.twig', [
            'devisType' => $devisType,
            'entrepreneur' => $entrepreneur,
        ]);
    }

    /**
     * Traitement de la soumission
     */
    #[Route('/devis/submit/{id}', name: 'app_entrepreneur_devis_submit', methods: ['POST'])]
    public function submit(
        DevisType $devisType, 
        Request $request, 
        EntityManagerInterface $em,
        EntrepreneurRepository $entrepreneurRepo,
        PrestataireRepository $prestataireRepo
    ): Response {
        $userEmail = $this->getUser()->getUserIdentifier();
        $entrepreneur = $entrepreneurRepo->findOneBy(['email' => $userEmail]);

        if (!$entrepreneur) {
            $this->addFlash('error', 'Erreur d\'identification de votre entreprise.');
            return $this->redirectToRoute('app_entrepreneur_dashboard');
        }

        $devisEnt = new DevisEntrepreneur();
        $devisEnt->setEntrepreneur($entrepreneur);
        $devisEnt->setChantier($devisType->getChantier());
        $devisEnt->setStatut('En attente');
        $chantier = $devisEnt->getChantier();
        if ($chantier) {
            $chantier->setStatut('Valider');
        }
        
        $dateDebut = new \DateTime($request->request->get('date_debut'));
        $devisEnt->setDateDebut($dateDebut);
        $devisEnt->setDureeEstimeeJour((int)$request->request->get('duree'));

        $em->persist($devisEnt);

        $tabPrix = $request->request->all('prix');
        
        foreach ($tabPrix as $prestataireId => $prixUnitaire) {
            if ($prixUnitaire !== null && $prixUnitaire !== '') {
                $ligneDevis = new DevisEntrepreneurPrestataire();
                $ligneDevis->setDevisEntrepeneur($devisEnt);
                
                $prestataire = $prestataireRepo->find($prestataireId);
                if ($prestataire) {
                    $ligneDevis->setPrestation($prestataire);
                    $ligneDevis->setPrixUnitaire((float)$prixUnitaire);
                    $em->persist($ligneDevis);
                }
            }
        }

        $em->flush();

        $this->addFlash('success', 'Votre devis a été envoyé avec succès !');
        return $this->redirectToRoute('app_entrepreneur_dashboard');
    }
    
    #[Route("/entrepreneur/mes-prestations", name:"app_entrepreneur_prestations")]
    public function mesPrestations(CategorieRepository $catRepo, EntrepreneurRepository $entrepreneurRepo): Response
    {
        // On cherche l'entrepreneur par l'email de l'utilisateur connecté
        $userEmail = $this->getUser()->getUserIdentifier();
        $entrepreneur = $entrepreneurRepo->findOneBy(['email' => $userEmail]);

        if (!$entrepreneur) {
            throw $this->createNotFoundException("Profil entrepreneur introuvable.");
        }
        
        return $this->render('entrepreneur/prestations.html.twig', [
            'entrepreneur' => $entrepreneur,
            'toutes_les_categories' => $catRepo->findAll(),
        ]);
    }

    #[Route("/entrepreneur/update-prestations", name:"app_entrepreneur_update_prestations", methods:["POST"])]
    public function updatePrestations(
        Request $request, 
        EntityManagerInterface $em, 
        EntrepreneurRepository $entrepreneurRepo
    ): Response {
        $userEmail = $this->getUser()->getUserIdentifier();
        $entrepreneur = $entrepreneurRepo->findOneBy(['email' => $userEmail]);

        if (!$entrepreneur) {
            $this->addFlash('error', 'Entrepreneur non trouvé.');
            return $this->redirectToRoute('app_entrepreneur_dashboard');
        }

        $categoriesIds = $request->request->all('categories') ?? [];

        // 1. On nettoie les relations existantes
        // On retire les catégories
        foreach ($entrepreneur->getCategories() as $cat) {
            $entrepreneur->removeCategory($cat);
        }
        
        // On retire les prestations (Prestataires)
        // Note : vérifie si ta méthode est removePrestataire() ou removePrestation()
        foreach ($entrepreneur->getPrestataires() as $presta) {
            $entrepreneur->removePrestataire($presta);
        }

        // 2. On ajoute les nouvelles sélections
        if (!empty($categoriesIds)) {
            foreach ($categoriesIds as $id) {
                $categorie = $em->getRepository(Categorie::class)->find($id);
                
                if ($categorie) {
                    // On ajoute la catégorie
                    $entrepreneur->addCategory($categorie);
                    
                    // On ajoute toutes les prestations de cette catégorie à l'entrepreneur
                    foreach ($categorie->getPrestataires() as $prestation) {

                        $entrepreneur->addPrestataire($prestation);
                    }
                }
            }
        }

        // 3. On sauvegarde le tout
        $em->flush();

        $this->addFlash('success', 'Vos catégories et prestations ont été mises à jour avec succès !');
        return $this->redirectToRoute('app_entrepreneur_prestations');
    }
    #[Route('/devis/terminer/{id}', name: 'app_entrepreneur_devis_terminer', methods: ['POST'])]
    public function terminerChantier(
        DevisEntrepreneur $devis, 
        EntityManagerInterface $em, 
        EntrepreneurRepository $entrepreneurRepo
    ): Response {
        // 1. On récupère l'entrepreneur lié à l'utilisateur connecté
        $userEmail = $this->getUser()->getUserIdentifier();
        $entrepreneur = $entrepreneurRepo->findOneBy(['email' => $userEmail]);

        // 2. Sécurité : on vérifie que l'entrepreneur existe et qu'il est bien le propriétaire de ce devis
        if (!$entrepreneur || $devis->getEntrepreneur() !== $entrepreneur) {
            throw $this->createAccessDeniedException("Vous n'avez pas l'autorisation de terminer ce chantier.");
        }

        // 3. Mise à jour des statuts
        $devis->setStatut('Terminé');

        $chantier = $devis->getChantier();
        if ($chantier) {
            // Vérifie bien que la méthode dans ton entité Chantier est setStatut() 
            // ou setEtat() selon ce que tu as généré
            $chantier->setStatut('Terminé');
        }

        $em->flush();

        $this->addFlash('success', 'Félicitations ! Le chantier et le devis sont désormais marqués comme terminés.');
        
        return $this->redirectToRoute('app_entrepreneur_dashboard');
    }
}