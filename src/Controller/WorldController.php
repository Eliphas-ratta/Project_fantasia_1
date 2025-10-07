<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\World;
use App\Entity\WorldUserRole;
use App\Form\WorldType;
use App\Repository\FriendshipRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class WorldController extends AbstractController
{
    #[Route('/world', name: 'app_world_index')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $worldUserRoles = $em->getRepository(WorldUserRole::class)->findBy(['user' => $user]);

        return $this->render('world/index.html.twig', [
            'userWorlds' => $worldUserRoles,
        ]);
    }

    #[Route('/world/create', name: 'app_world_create')]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        FriendshipRepository $friendRepo
    ): Response {
        $user = $this->getUser();

        // Récupère la liste d’amis du joueur connecté
        $friends = $friendRepo->getFriends($user);

        $world = new World();
        $form = $this->createForm(WorldType::class, $world);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Infos principales
            $world->setCreatedBy($user);
            $world->setCreateAt(new \DateTime());

            // Upload image
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $fileName = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('world_images_directory'),
                    $fileName
                );
                $world->setImage($fileName);
            }

            $em->persist($world);
            $em->flush();

            // Le créateur devient ADMIN
            $creatorRole = new WorldUserRole();
            $creatorRole->setUser($user);
            $creatorRole->setWorld($world);
            $creatorRole->setRole('ADMIN');
            $em->persist($creatorRole);

            // Gestion des amis sélectionnés
            $selectedFriends = $request->request->all('friends') ?? [];

            foreach ($selectedFriends as $friendId => $data) {
                if (!isset($data['selected'])) continue;

                $friend = $em->getRepository(User::class)->find($friendId);
                if ($friend) {
                    $role = $data['role'] ?? 'VIEWER';
                    $friendRole = new WorldUserRole();
                    $friendRole->setUser($friend);
                    $friendRole->setWorld($world);
                    $friendRole->setRole($role);
                    $em->persist($friendRole);
                }
            }

            $em->flush();

            $this->addFlash('success', 'World created successfully!');
            return $this->redirectToRoute('app_world_index');
        }

        return $this->render('world/create.html.twig', [
            'form' => $form->createView(),
            'friends' => $friends,
        ]);
    }

    #[Route('/world/{id}', name: 'app_world_show')]
public function show(World $world): Response
{
    return $this->render('world/show.html.twig', [
        'world' => $world,
    ]);
}




}
