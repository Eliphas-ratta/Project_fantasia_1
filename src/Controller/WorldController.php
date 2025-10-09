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
use App\Repository\UserRepository;



// ✅ Intervention Image
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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

        // ✅ Récupère la liste d’amis du joueur connecté
        $friends = $friendRepo->findFriendsOfUser($user);

        $world = new World();
        $form = $this->createForm(WorldType::class, $world);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Infos principales
            $world->setCreatedBy($user);
            $world->setCreateAt(new \DateTime());

            // ✅ Upload + vérification + recadrage image
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $extension = strtolower($imageFile->guessExtension());
                $allowed = ['jpg', 'jpeg', 'png'];

                // 🔸 Vérifie le format autorisé
                if (!in_array($extension, $allowed)) {
                    $this->addFlash('danger', 'Invalid image format (only JPG and PNG are allowed).');
                    return $this->redirectToRoute('app_world_create');
                }

                $uploadDir = $this->getParameter('world_images_directory');

                // 🔸 Crée le dossier si inexistant
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0775, true);
                }

                // 🔸 Génère un nom de fichier unique
                $fileName = uniqid() . '.' . $extension;
                $filePath = $uploadDir . '/' . $fileName;

                // 🔹 Création du gestionnaire d'image (GD ou Imagick)
                $manager = new ImageManager(new Driver());

                // 🔹 Lecture du fichier temporaire
                $image = $manager->read($imageFile->getPathname());

                // 🔹 Recadrage centré et redimension à 1024x1024
                $image->cover(1024, 1024, position: 'center');

                // 🔹 Sauvegarde avec une qualité de 90 %
                $image->save($filePath, 90);

                // 🔹 Suppression sécurisée de l'ancienne image (si jamais on édite plus tard)
                if ($world->getImage() && file_exists($uploadDir . '/' . $world->getImage())) {
                    unlink($uploadDir . '/' . $world->getImage());
                }

                // Enregistre le nom de fichier
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
public function show(World $world, FriendshipRepository $friendRepo): Response
{
    $user = $this->getUser();
    $friends = [];

    if ($user) {
        $friends = $friendRepo->findFriendsOfUser($user);
    }

    return $this->render('world/show.html.twig', [
        'world' => $world,
        'friends' => $friends,
    ]);
}

#[Route('/world/{id}/add-member', name: 'app_world_add_member', methods: ['POST'])]
public function addMember(
    World $world,
    Request $request,
    EntityManagerInterface $em,
    UserRepository $userRepo
): Response {
    $user = $this->getUser();
    if (!$user) return $this->redirectToRoute('app_login');

    $friendId = $request->request->get('friend_id');
    if (!$friendId) return $this->redirectToRoute('app_world_show', ['id' => $world->getId()]);

    $friend = $userRepo->find($friendId);
    if (!$friend) return $this->redirectToRoute('app_world_show', ['id' => $world->getId()]);

    // Vérifie si déjà membre
    $existing = $em->getRepository(WorldUserRole::class)->findOneBy([
        'user' => $friend,
        'world' => $world,
    ]);

    if ($existing) {
        $this->addFlash('warning', 'This user is already in the world.');
        return $this->redirectToRoute('app_world_show', ['id' => $world->getId()]);
    }

    // Ajoute comme VIEWER par défaut
    $role = new WorldUserRole();
    $role->setUser($friend);
    $role->setWorld($world);
    $role->setRole('VIEWER');

    $em->persist($role);
    $em->flush();

    $this->addFlash('success', $friend->getUsername() . ' has been added to the world!');
    return $this->redirectToRoute('app_world_show', ['id' => $world->getId()]);
}


}
