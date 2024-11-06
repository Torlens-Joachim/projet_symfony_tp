<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\ConnexionFormType;
use App\Form\InscriptionFormType;
use App\Repository\UtilisateurRepository;
use App\Service\PasswordHasher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthentificationController extends AbstractController
{
    function __construct(private UtilisateurRepository $utilisateurRepo)
    {
        $this->utilisateurRepo = $utilisateurRepo;
    }

    // AUTHENTIFICATION
    #[Route("/authentification", name: "app_auth", methods: ["GET", "POST"])]
    public function inscription(Request $req, PasswordHasher $passwordHasher)
    {
        if($this->isGranted("IS_AUTHENTICATED_FULLY")) {
            return $this->redirectToRoute("app_profile");
        }

        $utilisateur = new Utilisateur();
        $formulaire = $this->createForm(InscriptionFormType::class, $utilisateur);
        $formulaire->handleRequest($req);

        $formulaireConnexion = $this->createForm(ConnexionFormType::class, $utilisateur);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            return $this->traitementInscription($passwordHasher, $utilisateur);
        }
        return $this->render("pages/auth.html.twig", ['formulaireInscription' => $formulaire, 'formulaireConnexion' => $formulaireConnexion]);
    }

    // CONNEXION
    #[Route('/login', name: 'app_login')]
    function connexion()
    {
        return $this->redirectToRoute("app_auth", ["connexion" => ["status" => "error", "code" => "CREDENTIAL_INVALID"]]);
    }

    // TRAITEMENT
    private function traitementInscription(PasswordHasher $passwordHasher, Utilisateur $utilisateur)
    {
        $userExist = $this->utilisateurRepo->findOneBy(["email" => $utilisateur->getEmail()]);

        if ($userExist) {
            return $this->redirectToRoute("app_auth", ["inscription" => ["status" => 'error', "code" => 'USER_EXIST']]);
        }

        $passwordHasher->hash($utilisateur);
        $this->utilisateurRepo->sauvegarder($utilisateur, true);
        return $this->redirectToRoute("app_auth", ["inscription" => ["status" => 'success', "code" => 'INSCRIPTION_SUCCESS']]);
    }

    #[Route("/profile", name: "app_profile", methods: ["GET"])]
    public function ProfileController()
    {
        if(!$this->isGranted("IS_AUTHENTICATED_FULLY")) {
            return $this->redirectToRoute("app_auth");
        }

        return $this->render("pages/profile/index.html.twig");
    }

    #[Route("/deconnexion", name: "app_deconnexion")]
    public function logout()
    {}

}
