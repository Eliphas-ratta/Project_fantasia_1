<?php

namespace App\Controller;

use App\Entity\World;
use App\Entity\Faction;
use App\Form\FactionType;
use App\Repository\FactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

#[Route('/world')]
class FactionController extends AbstractController
{
    #[Route('/{id}/factions', name: 'app_faction_index', methods: ['GET'])]
    public function index(World $world, FactionRepository $factionRepository): Response
    {
        $factions = $factionRepository->findBy(['world' => $world]);
        $userRole = null;

        if ($this->getUser()) {
            /** @var User $currentUser */
            $currentUser = $this->getUser();
            $userRole = $world->getRoleForUser($currentUser);
        }

        return $this->render('faction/index.html.twig', [
            'world' => $world,
            'factions' => $factions,
            'userRole' => $userRole,
        ]);
    }

    // ✅ Route création d'une nouvelle Faction
    #[Route('/{id}/faction/create', name: 'app_faction_create', methods: ['GET', 'POST'])]
    public function create(Request $request, World $world, EntityManagerInterface $em): Response
    {
        $faction = new Faction();
        $faction->setWorld($world);

        $form = $this->createForm(FactionType::class, $faction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move($this->getParameter('kernel.project_dir') . '/public/uploads/faction_images', $newFilename);
                $faction->setImage($newFilename);
            }

            $em->persist($faction);
            $em->flush();
            $this->addFlash('success', 'Faction created successfully!');
            return $this->redirectToRoute('app_faction_index', ['id' => $world->getId()]);
        }

        return $this->render('faction/create.html.twig', [
            'world' => $world,
            'form' => $form,
        ]);
    }
}
