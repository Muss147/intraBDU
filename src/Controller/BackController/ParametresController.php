<?php

namespace App\Controller\BackController;

use App\Entity\Parametres;
use App\Form\ParametresType;
use App\Repository\ParametresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/espace-admin/parametres')]
final class ParametresController extends AbstractController
{
    public function __construct(
        private SluggerInterface $slugger,
        private EntityManagerInterface $em
    ) {}

    #[Route('/liste-{type}/{parent?}', name: 'param_type')]
    public function param_type(Request $request, $parent, $type, ParametresRepository $parametresRepository, SessionInterface $session): Response
    {
        $session->set('menu', null);
        $session->set('subMenu', $type);

        $dataParent = null;
        if ($parent) {
            if (ctype_digit($parent)) {
                $dataParent = $parametresRepository->find($parent);
                $parametres = $this->em->getRepository(Parametres::class)->findBy([
                    'type' => $type,
                    'parent' => $dataParent
                ], []);
            }
            else {
                $dataParent = $parametresRepository->findOneByType($parent);
                $parametres = $this->em->getRepository(Parametres::class)->findByType($type);
            }
        }
        else $parametres = $this->em->getRepository(Parametres::class)->findByType($type);

        $parametre = new Parametres();
        // Pré-remplir les actions possibles de cette permission
        $parametre->setType($type);
        // $parametre->setParent($dataParent);

        $form = $this->createForm(ParametresType::class, $parametre, ['type' => $dataParent ? $dataParent->getType() : null]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $parametre->generateSlug();
            $parametre->updatedTimestamps();
            $parametre->updatedUserstamps($this->getUser());

            $this->em->persist($parametre);
            $this->em->flush();
    
            $this->addFlash('success', $type .' ajouté(e) avec succès.');
            return $this->redirectToRoute('param_type', ['type' => $type, 'parent' => $parent ?? null]);
        }
        return $this->render('back/parametres/liste.html.twig', [
            'parametres' => $parametres,
            'new_param' => $form,
            'type' => $type,
            'parent' => $dataParent
        ]);
    }

    #[Route('/modification', name: 'param_edit')]
    public function editParam(Request $request, ParametresRepository $parametresRepository): Response
    {
        if ($request->isMethod('POST') &&
            $parametre = $parametresRepository->find($request->get('param_id'))) {
            
            $libelle = $request->get('param_libelle');

            $parametre->setLibelle($libelle)->generateSlug();
            $parametre->updatedTimestamps();
            $parametre->updatedUserstamps($this->getUser());

            $this->em->persist($parametre);
            $this->em->flush();
            $this->addFlash('success', 'Modification de <b>'. $libelle .'</b> effectuée avec succès.');
        }
        return $this->redirectToRoute('param_type', ['type' => $parametre->getType(), 'parent' => $parametre->getParent() ?? null]);
    }

    #[Route('/ajout-de-sous-element', name: 'add_children')]
    public function addChildren(Request $request, ParametresRepository $parametresRepository): Response
    {
        if ($request->isMethod('POST') &&
            $parent = $parametresRepository->find($request->get('element_parent'))) {
            
            $type = str_replace(['/', '\\'], '-', $request->get('element_titre'));
            $libelle = $request->get('element_libelle');
            $description = $request->get('element_description');
            $child = new Parametres();

            $child->setLibelle($libelle)
                ->setParent($parent)
                ->setType($type)
                ->setDescription($description)
                ->updatedTimestamps();
            $child->generateSlug();
            $child->updatedUserstamps($this->getUser());

            foreach ($parametresRepository->findByParent($parent) as $key => $param) {
                $param->setType($type);
                $this->em->persist($param);
            }

            $this->em->persist($child);
            $this->em->flush();
            $this->addFlash('success', 'Ajout de <b>'. $libelle .'</b> effectuée avec succès.');
            return $this->redirectToRoute('param_type', ['type' => $type, 'parent' => $parent->getId() ?? null]);
        }
        else {
            $this->addFlash('error', 'Identifiant du parent introuvable.');
            return $this->redirect($request->headers->get('referer'));
        }
        
    }

    #[Route('/delete/{param}', name: 'param_delete', methods: ['POST'])]
    public function deleteCategorie(Request $request, Parametres $param): Response
    {
        if ($this->isCsrfTokenValid('delete'.$param->getId(), $request->getPayload()->getString('_token'))) {
            $param->remove($this->getUser());
            $this->em->flush();
        }
        $this->addFlash('success', 'Suppression effectuée avec succès.');
        return $this->redirectToRoute('param_type', ['type' => $param->getType(), 'parent' => $param->getParent() ? $param->getParent()->getId() : null]);
    }

    #[Route('/delete-params-selected', name: 'param_selected_delete', methods: ['POST'])]
    public function deleteUsersSelected(Request $request, ParametresRepository $parametresRepository): Response
    {
        // Récupérer les données JSON de la requête
        $data = json_decode($request->getContent(), true);

        if ($request->isXmlHttpRequest()) {
            foreach ($data['usersDeleted'] as $id) {
                if ($param = $parametresRepository->find($id)) $param->remove($this->getUser());
                $this->em->flush();
            }
        }
        return new JsonResponse(true, 200);
    }
}
