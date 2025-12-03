<?php

namespace App\Controller\BackController;

use App\Repository\FilesRepository;
use App\Entity\Files;
use App\Form\PagesForm;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\KernelInterface;

use function Symfony\Component\Clock\now;

#[Route('/espace-admin/pages-builder')]
final class ThemeController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private FileUploader $fileUploader
    ) {}

    #[Route('/theme-cover', name: 'theme.cover')]
    public function themeCover(
        Request $request,
        FilesRepository $filesRepository,
        SessionInterface $session,
        KernelInterface $kernel
    ): Response {
        $session->set('menu', 'pages_builder');
        $session->set('subMenu', 'theme_cover');

        // chemin racine du projet
        $projectRoot = $kernel->getProjectDir();
        // Exemple : chemin vers public/uploads
        $uploadPath = $projectRoot . '/public';

        // Récupérer ou créer les deux fichiers (desktop et mobile)
        $desktop = $filesRepository->findOneByType('theme cover desktop') ?? new Files();
        $mobile  = $filesRepository->findOneByType('theme cover mobile') ?? new Files();

        if ($request->isMethod('POST')) {
            // --- Suppression demandée ---
            $removeIds = $request->request->all('cover_remove');
            if (!empty($removeIds)) {
                foreach ($removeIds as $key => $id) {
                    if ($file = $filesRepository->find($key)) {
                        $this->fileUploader->delete($uploadPath . $file->getFilename()); // suppression physique
                        $this->em->remove($file);
                    }
                }
            }
            // 🔹 Gestion du fichier Desktop
            if ($file = $request->files->get('cover')['desktop'] ?? null) {
                $oldFile = $desktop->getFilename();

                $data = $this->fileUploader->upload($file);
                $desktop
                    ->setFilename($data['filename'])
                    ->setType('theme cover desktop')
                    ->setSize($data['size'])
                    ->setAlt('Theme Desktop du ' . date('d/m/Y'));

                if ($oldFile && file_exists($oldFile)) {
                    @unlink($oldFile);
                }

                $desktop->updatedTimestamps();
                $desktop->updatedUserstamps($this->getUser());
                $this->em->persist($desktop);
            }

            // 🔹 Gestion du fichier Mobile
            if ($file = $request->files->get('cover')['mobile'] ?? null) {
                $oldFile = $mobile->getFilename();

                $data = $this->fileUploader->upload($file);
                $mobile
                    ->setFilename($data['filename'])
                    ->setType('theme cover mobile')
                    ->setSize($data['size'])
                    ->setAlt('Theme Mobile du ' . date('d/m/Y'));

                if ($oldFile && file_exists($oldFile)) {
                    @unlink($oldFile);
                }

                $mobile->updatedTimestamps();
                $mobile->updatedUserstamps($this->getUser());
                $this->em->persist($mobile);
            }

            $this->em->flush();

            $this->addFlash('success', 'Thèmes (Desktop/Mobile) mis à jour avec succès.');
            return $this->redirectToRoute('theme.cover');
        }

        return $this->render('back/pages-builder/theme.html.twig', [
            'themeDesktop' => $desktop,
            'themeMobile'  => $mobile,
        ]);
    }
}
