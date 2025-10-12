<?php

namespace App\Controller;

use App\Entity\World;
use App\Entity\Faction;
use App\Repository\FactionRepository;
use App\Service\CurrentWorldService; // si tu utilises un service pour currentWorld
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/world')]
class FactionController extends AbstractController
{
    #[Route('/{id}/factions', name: 'app_faction_index', methods: ['GET'])]
    public function index(World $world, FactionRepository $factionRepository): Response
    {
        // On récupère toutes les factions du monde sélectionné
        $factions = $factionRepository->findBy(['world' => $world]);

        return $this->render('faction/index.html.twig', [
            'world' => $world,
            'factions' => $factions,
        ]);
    }
}
