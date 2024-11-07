<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UserFormType;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProfileController extends AbstractController
{
    #[Route("/profile/update", name: "app_modifier_informations")]
    function modifierProfile(UtilisateurRepository $repo, Request $req, SluggerInterface $slugger,
    #[Autowire('%kernel.project_dir%/public/uploads/avatar')] string $avatarsDirectory)
    {
        if (!$this->isGranted("IS_AUTHENTICATED_FULLY")) {
            return $this->redirectToRoute("app_auth");
        }

        $utilisateurFromSession = $this->getUser()->getUserIdentifier();

        // recupere de la db
        $utilisateur = $repo->findOneBy(["email" => $utilisateurFromSession]);

        $formulaire = $this->createForm(UserFormType::class, $utilisateur);

        $formulaire->handleRequest($req);
        
        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $avatarFile = $formulaire->get("avatar")->getData();

            if($avatarFile) {
                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                
                $newFilename = $safeFilename.'-'.uniqid().'.'.$avatarFile->guessExtension();
                try {
                    $avatarFile->move($avatarsDirectory, $newFilename);
                } catch (FileException $e) {
                    $this->redirectToRoute("app_modifier_informations",  ["uploading_img" => ["status" => "error", "code" => "UPLOADING_IMAGE_FAILED"]]);
                }

                
                $utilisateur->setAvatar($newFilename);
            }
            
            $repo->sauvegarder($utilisateur, true);

            return $this->redirectToRoute("app_home");
        }

        return $this->render("pages/profile/profile.update.html.twig", ["formulaire" => $formulaire->createView()]);
    }
}
