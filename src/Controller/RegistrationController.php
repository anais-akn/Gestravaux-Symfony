<?php
namespace App\Controller;
use App\Entity\Utilisateur; 
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Inspecteur;
use App\Entity\Entrepreneur;
 
class RegistrationController extends AbstractController

{
    #[Route('/register-choice', name: 'app_register_choice')]
    public function registerChoice(): Response
    {
        return $this->render('registration/choice.html.twig');
    }

    #[Route('/register-inspecteur', name: 'app_register_inspecteur')]
    public function registerInspecteur(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $user = new Utilisateur();
            $this->fillUserData($user, $request, $userPasswordHasher);
            $user->setRoles(['ROLE_INSPECTEUR']);

            $inspect = new Inspecteur();
            $this->fillBusinessData($inspect, $request);

            $entityManager->persist($inspect);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }
        return $this->render('registration/register.html.twig');
    }

    #[Route('/register-proprietaire', name: 'app_register_proprietaire')]
    public function registerProprietaire(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $user = new Utilisateur();
            $this->fillUserData($user, $request, $userPasswordHasher);
            $user->setRoles(['ROLE_USER']);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }
        return $this->render('registration/register.html.twig');
    }

    #[Route('/register-entrepreneur', name: 'app_register_entrepreneur')]
    public function registerEntrepreneur(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $user = new Utilisateur();
            $this->fillEntrepriseData($user, $request, $userPasswordHasher);
            $user->setRoles(['ROLE_ENTREPRENEUR']);
            
            $entrep = new Entrepreneur();
            $this->fillBusinessEnterpriseData($entrep, $request);

            $entityManager->persist($entrep);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }
        return $this->render('registration/register-entr.html.twig');
    }


    private function fillUserData(Utilisateur $user, Request $request, UserPasswordHasherInterface $hasher): void
    {
        $user->setEmail($request->request->get('email'));
        $user->setNom($request->request->get('nom'));
        $user->setPrenom($request->request->get('prenom'));
        $user->setPassword($hasher->hashPassword($user, $request->request->get('password')));
        $user->setTelephone($request->request->get('telephone'));
        $user->setAdresse($request->request->get('adresse'));
        $user->setVille($request->request->get('ville'));
        $user->setCodePostal($request->request->get('codePostal'));
    }
    private function fillEntrepriseData(Utilisateur $user, Request $request, UserPasswordHasherInterface $hasher): void
    {
        $user->setEmail($request->request->get('email'));
        $user->setNom($request->request->get('nom'));
        $user->setPrenom("");
        $user->setPassword($hasher->hashPassword($user, $request->request->get('password')));
        $user->setTelephone($request->request->get('telephone'));
        $user->setAdresse($request->request->get('adresse'));
        $user->setVille($request->request->get('ville'));
        $user->setCodePostal($request->request->get('codePostal'));
    }

    private function fillBusinessData($entity, Request $request): void
    {
        $entity->setEmail($request->request->get('email'));
        $entity->setNom($request->request->get('nom'));
        $entity->setPrenom($request->request->get('prenom'));
        $entity->setTelephone($request->request->get('telephone'));
        $entity->setAdresse($request->request->get('adresse'));
        $entity->setVille($request->request->get('ville'));
        $entity->setCodePostal($request->request->get('codePostal'));
    }
    
    private function fillBusinessEnterpriseData($entity, Request $request): void
    {
        $entity->setEmail($request->request->get('email'));
        $entity->setNom($request->request->get('nom'));
        $entity->setSiret($request->request->get('siret'));
        $entity->setTelephone($request->request->get('telephone'));
        $entity->setAdresse($request->request->get('adresse'));
        $entity->setVille($request->request->get('ville'));
        $entity->setCodePostal($request->request->get('codePostal'));
    }
}
