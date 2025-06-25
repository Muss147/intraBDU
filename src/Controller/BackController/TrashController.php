<?php
// src/Controller/TrashController.php
namespace App\Controller\BackController;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\TrashRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

#[Route('/espace-admin/corbeille')]
class TrashController extends AbstractController
{
    public function __construct(
        private TrashRepository $trashRepository,
        private EntityManagerInterface $em
    ) {}

    #[Route('/', name: 'trash_list')]
    public function index(SessionInterface $session): Response
    {
        // Définition du menu actif
        $session->set('menu', 'trash');
        $session->set('subMenu', '');
        $deletedItems = $this->trashRepository->findAll();

        return $this->render('back/security/corbeille.html.twig', [
            'deletedItems' => $deletedItems,
        ]);
    }

    #[Route('/restore/{class}/{id}', name: 'trash_restore')]
    public function restore(string $class, int $id): RedirectResponse
    {
        $entity = $this->em->getRepository($class)->find($id);

        if ($entity && method_exists($entity, 'setDeletedAt')) {
            $entity->setDeletedAt(null);
            $entity->setDeletedUser(null);
            if (method_exists($entity, 'setDeletedIp')) {
                $entity->setDeletedIp(null);
            }

            $this->em->flush();
            // $this->addFlash('success', 'Élément restauré avec succès.');
        }

        return $this->redirectToRoute('trash_list');
    }

    #[Route('/delete/{class}/{id}', name: 'trash_delete_permanent')]
    public function deletePermanent(string $class, int $id): RedirectResponse
    {
        $entity = $this->em->getRepository($class)->find($id);

        if ($entity) {
            $this->em->remove($entity);
            $this->em->flush();

            // $this->addFlash('success', 'Élément supprimé définitivement.');
        }

        return $this->redirectToRoute('trash_list');
    }

    #[Route('/empty-all-trash', name: 'empty_trash', methods: ['POST'])]
    public function emptyAllTrash(Request $request): Response
    {
        $trash = $this->trashRepository->findAll();
        if ($this->isCsrfTokenValid('delete'.count($trash), $request->getPayload()->getString('_token'))) {
            foreach ($trash as $key => $item) $this->em->remove($item);
            $this->em->flush();
        }

        return $this->redirectToRoute('trash_list', [], Response::HTTP_SEE_OTHER);
    }

}