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
    if (!$user) {
        return $this->json(['error' => 'You must be logged in.'], 401);
    }

    $friendUsername = $request->request->get('friend_username');
    if (!$friendUsername) {
        return $this->json(['error' => 'No friend selected.'], 400);
    }

    $friend = $userRepo->findOneBy(['username' => $friendUsername]);
    if (!$friend) {
        return $this->json(['error' => 'Friend not found.'], 404);
    }

    // Vérifie si déjà membre
    $existing = $em->getRepository(WorldUserRole::class)->findOneBy([
        'user' => $friend,
        'world' => $world,
    ]);

    if ($existing) {
        return $this->json(['error' => 'This user is already in the world.'], 400);
    }

    // Ajoute comme VIEWER par défaut
    $role = new WorldUserRole();
    $role->setUser($friend);
    $role->setWorld($world);
    $role->setRole('VIEWER');

    $em->persist($role);
    $em->flush();

    return $this->json([
        'success' => true,
        'username' => $friend->getUsername(),
        'role' => $role->getRole(),
    ]);
}


#[Route('/world/{id}/admin/{section}', name: 'app_world_admin', defaults: ['section' => 'world'])]

public function admin(World $world, string $section = 'world', FriendshipRepository $friendRepo): Response
{
    $user = $this->getUser();
    $role = $world->getRoleForUser($user);

    if ($role !== 'ADMIN') {
        throw $this->createAccessDeniedException('You are not an admin of this world.');
    }

    $friends = $friendRepo->findFriendsOfUser($user);

    // 🔹 Tu pourras charger les logs ici si nécessaire
    $logs = ($section === 'log') ? [] : null;

    return $this->render('world/admin.html.twig', [
        'world' => $world,
        'users' => $world->getWorldUserRoles(),
        'friends' => $friends,
        'logs' => $logs,
        'section' => $section,
    ]);
}


#[Route('/world/{id}/update', name: 'app_world_update', methods: ['POST'])]
public function update(
    World $world,
    Request $request,
    EntityManagerInterface $em
): Response {
    $user = $this->getUser();

    // Vérifie que le user est bien admin de ce monde
    $role = $world->getRoleForUser($user);
    if ($role !== 'ADMIN') {
        throw $this->createAccessDeniedException('You are not allowed to update this world.');
    }

    // Récupère les données du formulaire
    $name = trim($request->request->get('name'));
    $description = trim($request->request->get('description'));
    $imageFile = $request->files->get('image');

    if ($name) {
        $world->setName($name);
    }

    if ($description) {
        $world->setDescription($description);
    }

    // ✅ Si une nouvelle image est uploadée
    if ($imageFile) {
        $uploadDir = $this->getParameter('world_images_directory');

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $extension = strtolower($imageFile->guessExtension());
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($extension, $allowed)) {
            $this->addFlash('danger', 'Invalid image format.');
            return $this->redirectToRoute('app_world_admin', ['id' => $world->getId()]);
        }

        $fileName = uniqid() . '.' . $extension;
        $filePath = $uploadDir . '/' . $fileName;

        // Conversion avec Intervention Image
        $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
        $image = $manager->read($imageFile->getPathname());
        $image->cover(1024, 1024, position: 'center')->save($filePath, 85);

        // Supprime l’ancienne image si elle existe
        if ($world->getImage() && file_exists($uploadDir . '/' . $world->getImage())) {
            unlink($uploadDir . '/' . $world->getImage());
        }

        $world->setImage($fileName);
    }

    $em->flush();

    $this->addFlash('success', 'World updated successfully!');
    return $this->redirectToRoute('app_world_admin', ['id' => $world->getId()]);
}

#[Route('/world/{id}/delete', name: 'app_world_delete', methods: ['POST'])]
public function delete(World $world, EntityManagerInterface $em): Response
{
    $user = $this->getUser();

    // 🔒 Vérifie que l'utilisateur est bien admin du monde
    $role = $world->getRoleForUser($user);
    if ($world->getCreatedBy() !== $user) {
    throw $this->createAccessDeniedException('Only the world creator can delete this world.');
}

    // 🧹 Supprime d'abord les relations WorldUserRole
    foreach ($world->getWorldUserRoles() as $wur) {
        $em->remove($wur);
    }

    // 🧼 Supprime l'image du monde si elle existe
    $uploadDir = $this->getParameter('world_images_directory');
    if ($world->getImage() && file_exists($uploadDir . '/' . $world->getImage())) {
        unlink($uploadDir . '/' . $world->getImage());
    }

    // 🗑️ Supprime le monde
    $em->remove($world);
    $em->flush();

    $this->addFlash('info', 'World deleted successfully.');
    return $this->redirectToRoute('app_world_index');
}

#[Route('/world/{id}/remove-member/{userId}', name: 'app_world_remove_member')]
public function removeMember(World $world, int $userId, EntityManagerInterface $em): Response
{
    $user = $this->getUser();
    $memberRole = $em->getRepository(WorldUserRole::class)->findOneBy([
        'user' => $userId,
        'world' => $world,
    ]);

    if (!$memberRole) {
        $this->addFlash('warning', 'This user is not part of this world.');
        return $this->redirectToRoute('app_world_show', ['id' => $world->getId()]);
    }

    $memberUser = $memberRole->getUser();
    $creator = $world->getCreatedBy();
    $currentRole = $world->getRoleForUser($user);

    // ✅ Cas 1 : l'utilisateur se retire lui-même (autorisé)
    if ($memberUser === $user) {
        // Empêche le créateur de se retirer
        if ($memberUser === $creator) {
            $this->addFlash('danger', 'The world creator cannot leave their own world.');
            return $this->redirectToRoute('app_world_show', ['id' => $world->getId()]);
        }

        $em->remove($memberRole);
        $em->flush();

        $this->addFlash('info', 'You have left the world.');
        return $this->redirectToRoute('app_world_index');
    }

    // ✅ Cas 2 : un admin retire quelqu’un d’autre
    if ($currentRole !== 'ADMIN') {
        throw $this->createAccessDeniedException('You are not allowed to remove members.');
    }

    // 🚫 Empêche de retirer le créateur
    if ($memberUser === $creator) {
        $this->addFlash('danger', 'The world creator cannot be removed.');
        return $this->redirectToRoute('app_world_admin', ['id' => $world->getId()]);
    }

    // 🚫 Empêche un admin non-créateur de retirer un autre admin
    if ($memberRole->getRole() === 'ADMIN' && $creator !== $user) {
        $this->addFlash('danger', 'Only the world creator can remove another admin.');
        return $this->redirectToRoute('app_world_admin', ['id' => $world->getId()]);
    }

    // 🚫 Empêche de supprimer le dernier admin
    if ($memberRole->getRole() === 'ADMIN') {
        $adminCount = count(array_filter(
            $world->getWorldUserRoles()->toArray(),
            fn($wur) => $wur->getRole() === 'ADMIN'
        ));
        if ($adminCount <= 1) {
            $this->addFlash('danger', 'You cannot remove the last admin.');
            return $this->redirectToRoute('app_world_admin', ['id' => $world->getId()]);
        }
    }

    // ✅ Suppression autorisée
    $em->remove($memberRole);
    $em->flush();

    $this->addFlash('info', 'User removed from world.');
    return $this->redirectToRoute('app_world_admin', ['id' => $world->getId()]);
}



#[Route('/world/{id}/update-role', name: 'app_world_update_role', methods: ['POST'])]
public function updateRole(
    World $world,
    Request $request,
    EntityManagerInterface $em
): Response {
    $user = $this->getUser();
    $role = $world->getRoleForUser($user);

    if ($role !== 'ADMIN') {
        return $this->json(['error' => 'Access denied'], 403);
    }

    $userId = $request->request->get('userId');
    $newRole = strtoupper($request->request->get('role'));

    if (!in_array($newRole, ['ADMIN', 'MODERATOR', 'VIEWER'])) {
        return $this->json(['error' => 'Invalid role'], 400);
    }

    $targetRole = $em->getRepository(\App\Entity\WorldUserRole::class)->findOneBy([
        'world' => $world,
        'user' => $userId,
    ]);

    if (!$targetRole) {
        return $this->json(['error' => 'User not found in this world'], 404);
    }

    $targetUser = $targetRole->getUser();

 // ✅ Protection : Seul le créateur peut modifier un ADMIN
$creatorId = method_exists($world->getCreatedBy(), 'getId') ? $world->getCreatedBy()->getId() : null;
$currentUserId = method_exists($this->getUser(), 'getId') ? $this->getUser()->getId() : null;

if (
    $targetRole->getRole() === 'ADMIN' &&
    $creatorId !== null &&
    $currentUserId !== null &&
    $creatorId !== $currentUserId
) {
    return $this->json(['error' => 'Only the world creator can modify or demote another admin.'], 403);
}





    // ✅ Protection : Un admin ne peut pas se modifier lui-même
    if ($targetUser === $user) {
        return $this->json(['error' => 'You cannot change your own role.'], 400);
    }

    // ✅ Protection : empêche de supprimer le dernier admin
    if ($targetRole->getRole() === 'ADMIN' && $newRole !== 'ADMIN') {
        $adminCount = count(array_filter(
            $world->getWorldUserRoles()->toArray(),
            fn($wur) => $wur->getRole() === 'ADMIN'
        ));
        if ($adminCount <= 1) {
            return $this->json(['error' => 'Cannot remove the last admin.'], 400);
        }
    }

    $targetRole->setRole($newRole);
    $em->flush();

    return $this->json([
        'success' => true,
        'newRole' => $newRole,
        'message' => sprintf('%s role updated to %s', $targetUser->getUsername(), $newRole)
    ]);
}




}
