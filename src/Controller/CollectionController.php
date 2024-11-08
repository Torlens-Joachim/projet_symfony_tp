<?php

namespace App\Controller;

use App\Entity\Collection;
use App\Entity\Utilisateur;
use App\Form\CollectionFormType;
use App\Repository\CollectionRepository;
use App\Repository\UtilisateurRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CollectionController extends AbstractController
{
    #[Route('/collection/ajouter', name: 'app_ajouter_collection')]
    public function index(
        Request $req,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads/collection')] string $coversDirectory,
        UtilisateurRepository $repo
    ): Response {
        if (!$this->isGranted("IS_AUTHENTICATED_FULLY")) {
            $this->redirectToRoute("app_auth");
        }

        $collection = new Collection();

        $formulaire = $this->createForm(CollectionFormType::class, $collection);
        $formulaire->handleRequest($req);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $collection->setDate(new DateTime());

            $coverFile = $formulaire->get("cover")->getData();

            if ($coverFile) {
                $originalFilename = pathinfo($coverFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);

                $newFilename = $safeFilename . '-' . uniqid() . '.' . $coverFile->guessExtension();
                try {
                    $coverFile->move($coversDirectory, $newFilename);
                } catch (FileException $e) {
                    $this->redirectToRoute("app_ajouter_collection",  ["uploading_img" => ["status" => "error", "code" => "UPLOADING_IMAGE_FAILED"]]);
                }

                $collection->setCover($newFilename);
            }

            $utilisateurFromSession = $this->getUser()->getUserIdentifier();

            // recupere de la db
            $utilisateur = $repo->findOneBy(["email" => $utilisateurFromSession]);

            $utilisateur->addCollection($collection);

            $repo->sauvegarder($utilisateur, true);

            return $this->redirectToRoute("app_profile", ["add_collection_success" => ["status" => "success", "code" => "COLLECTION_SAVED"]]);
        }

        return $this->render('pages/collection/ajouter.html.twig', [
            "formulaire" => $formulaire->createView()
        ]);
    }

    #[Route("/collection/{id}/modifier", name: "app_modifier_collection")]
    public function modifierCollection(Request $req , $id, CollectionRepository $repo,  SluggerInterface $slugger,
    #[Autowire('%kernel.project_dir%/public/uploads/collection')] string $coversDirectory, UtilisateurRepository $utilisateurRepo)
    {
        $collection = $repo->find($id);

        $formulaire = $this->createForm(CollectionFormType::class, $collection);
        $formulaire->handleRequest($req);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $utilisateurFromSession = $this->getUser()->getUserIdentifier();

            $coverFile = $formulaire->get("cover")->getData();

            if ($coverFile) {
                $originalFilename = pathinfo($coverFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);

                $newFilename = $safeFilename . '-' . uniqid() . '.' . $coverFile->guessExtension();
                try {
                    $coverFile->move($coversDirectory, $newFilename);
                } catch (FileException $e) {
                    $this->redirectToRoute("app_ajouter_collection",  ["uploading_img" => ["status" => "error", "code" => "UPLOADING_IMAGE_FAILED"]]);
                }

                $collection->setCover($newFilename);
            }

            // recupere de la db
            $utilisateur = $utilisateurRepo->findOneBy(["email" => $utilisateurFromSession]);

            $utilisateur->addCollection($collection);
            $utilisateurRepo->sauvegarder($utilisateur, true);

            return $this->redirectToRoute("app_profile", ["edit_collection_success" => ["status" => "success", "code" => "COLLECTION_SAVED"]]);
        }

        return $this->render("pages/collection/modifier.html.twig", ["formulaire"=>$formulaire->createView()]);
    }
}
