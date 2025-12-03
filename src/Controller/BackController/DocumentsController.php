<?php

namespace App\Controller\BackController;

use App\Entity\Files;
use App\Entity\Documents;
use App\Form\DocumentsForm;
use App\Service\FileUploader;
use App\Repository\DocumentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/espace-admin/documents-de-reference')]
final class DocumentsController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private FileUploader $fileUploader
    ) {}

    #[Route('/lsite-{type}/{parent?}', name: 'app_documents')]
    public function procedures(Request $request, $type, $parent, DocumentsRepository $documentsRepository, SessionInterface $session): Response
    {
        $session->set('menu', 'documents');
        $session->set('subMenu', strtolower($type));
        
        if ($parent) $parent = $documentsRepository->find($parent);
        $documents = $this->em->getRepository(Documents::class)->findBy([
            'type' => $type,
            'parent' => $parent,
            'deletedAt' => NULL
        ], []);

        $document = new Documents();
        // Pré-remplir les actions possibles de cette permission
        $document->setType($type);
        $document->setParent($parent);

        $fileForm = $this->createForm(DocumentsForm::class, $document, ['form_type' => 'file']);
        $folderForm = $this->createForm(DocumentsForm::class, $document, ['form_type' => 'folder']);
        $fileForm->handleRequest($request);
        $folderForm->handleRequest($request);
        
        if ($fileForm->isSubmitted() && !$fileForm->isValid()) {
            foreach ($fileForm->getErrors(true) as $error) {
                $this->addFlash('error', $error->getMessage());
            }
        }
        if (($fileForm->isSubmitted() && $fileForm->isValid()) || ($folderForm->isSubmitted() && $folderForm->isValid())) {
            // Gestion de l'upload des fichiers
            if ($file = $fileForm->get('source')->getData()) {
                $data = $this->fileUploader->upload($file);
                $fileEntity = (new Files())
                    ->setFilename($data['filename'])
                    ->setType($data['type'])
                    ->setSize($data['size'])
                    ->setAlt($document->getLibelle());
                $document->setSource($fileEntity);
                $this->em->persist($fileEntity);
            }

            $document->generateSlug();
            $document->updatedTimestamps();
            $document->updatedUserstamps($this->getUser());

            $this->em->persist($document);
            $this->em->flush();
    
            $this->addFlash('success', $type .' ajouté(e) avec succès.');
            return $this->redirectToRoute('app_documents', ['type' => $type, 'parent' => $parent ? $parent->getId() : null]);
        }
        return $this->render('back/documents-reference/liste.html.twig', [
            'new_file' => $fileForm,
            'new_folder' => $folderForm,
            'type' => $type,
            'documents' => $documents,
            'dossiers' => $documentsRepository->findAllDossier($type),
            'parent' => $parent
        ]);
    }

    #[Route('/modification', name: 'document_edit')]
    public function editParam(Request $request, DocumentsRepository $documentsRepository): Response
    {
        if ($request->isMethod('POST') &&
            $parametre = $documentsRepository->find($request->get('param_id'))) {
            
            $libelle = $request->get('param_libelle');
            if ($libelle) $parametre->setLibelle($libelle);

            $parent = $request->get('param_parent');
            if ($parent) $parametre->setParent($documentsRepository->find($parent));
            elseif (!$libelle) $parametre->setParent(null); // En cas d'absence de $libelle => retour au dossier racine

            $parametre->generateSlug();
            $parametre->updatedTimestamps();
            $parametre->updatedUserstamps($this->getUser());

            $this->em->persist($parametre);
            $this->em->flush();
            $this->addFlash('success', 'Modification de <b>'. $libelle .'</b> effectuée avec succès.');
        }
        return $this->redirectToRoute('app_documents', ['type' => $parametre->getType(), 'parent' => $parametre->getParent() ? $parametre->getParent()->getId() : null]);
    }

    #[Route('/delete/{document}', name: 'document_delete', methods: ['POST'])]
    public function deleteCategorie(Request $request, Documents $document): Response
    {
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->getPayload()->getString('_token'))) {
            $document->remove($this->getUser());
            foreach ($document->getChildren() as $child) {
                $child->remove($this->getUser());
            }
            $this->em->flush();
        }
        $this->addFlash('success', 'Suppression effectuée avec succès.');
        return $this->redirectToRoute('app_documents', ['type' => $document->getType(), 'parent' => $document->getParent() ? $document->getParent()->getId() : null]);
    }

    #[Route('/delete-documents-selected', name: 'document_selected_delete', methods: ['POST'])]
    public function deleteUsersSelected(Request $request, DocumentsRepository $documentsRepository): Response
    {
        // Récupérer les données JSON de la requête
        $data = json_decode($request->getContent(), true);

        if ($request->isXmlHttpRequest()) {
            foreach ($data['docsToDelete'] as $id) {
                if ($param = $documentsRepository->find($id)) $param->remove($this->getUser());
                foreach ($param->getChildren() as $child) {
                    $child->remove($this->getUser());
                }
                $this->em->flush();
            }
        }
        return new JsonResponse(true, 200);
    }
}
