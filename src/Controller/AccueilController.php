<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route("/", name: "app_home", methods: ["GET"])]
    public function index()
    {
        return $this->render("pages/index.html.twig");
    }
}