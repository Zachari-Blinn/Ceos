<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Entity\Appreciation;
use App\Entity\Enseignement;
use App\Form\AppreciationType;
use App\Repository\EleveRepository;
use App\Repository\AppreciationRepository;
use App\Repository\EnseignementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/appreciation")
 */
class AppreciationController extends AbstractController
{
    /**
     * @Route("/", name="appreciation_index", methods={"GET"})
     */
    public function index(AppreciationRepository $appreciationRepository): Response
    {
        return $this->render('appreciation/index.html.twig', [
            'appreciations' => $appreciationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="appreciation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $appreciation = new Appreciation();
        $form = $this->createForm(AppreciationType::class, $appreciation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($appreciation);
            $entityManager->flush();

            return $this->redirectToRoute('appreciation_index');
        }

        return $this->render('appreciation/new.html.twig', [
            'appreciation' => $appreciation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_PROF")
     * @Route("/classe", name="appreciation_classe", methods={"GET"})
     */
    public function selectClasse(EnseignementRepository $enseignementRepository): Response
    {
        $currentUser = $this->getUser()->getProf()->getId();

        $classes = $enseignementRepository->findGroupBy($currentUser);

        return $this->render('appreciation/classe.html.twig', [
            'classes' => $classes,
        ]);
    }

    /**
     * @IsGranted("ROLE_PROF")
     * @Route("/{classe}/matiere", name="appreciation_matiere", methods={"GET"})
     */
    public function selectMatiere(Classe $classe, EnseignementRepository $enseignementRepository): Response
    {
        $currentUser = $this->getUser()->getProf()->getId();

        $matieres = $enseignementRepository->findBy(['prof' => $currentUser, 'classe' => $classe->getId()]);

        return $this->render('appreciation/matiere.html.twig', [
            'matieres' => $matieres,
            'classe' => $classe->getId(),
        ]);
    }

    /**
     * @IsGranted("ROLE_PROF")
     * @Route("/{classe}/{matiere}/commenter", name="evaluation_commenter", methods={"GET","POST"})
     */
    public function commenterEleve($classe, $matiere, Request $request, EleveRepository $eleveRepository, EnseignementRepository $enseignementRepository)
    {
        $prof = $this->getUser()->getProf()->getId();

        $enseignement = $enseignementRepository->findOneBy(['prof' => $prof, 'classe' => $classe, 'matiere' => $matiere]);

        if (!$enseignement) return $this->redirectToRoute('appreciation_index');

        $entityManager = $this->getDoctrine()->getManager();

        // On récupère les notes de l'évaluation
        $appreciations = $enseignement->getAppreciation();

        // On récupère tous les élèves
        $classeId = $enseignement->getClasse()->getId();
        $eleves = $eleveRepository->findBy(['classe' => $classeId]);

        // Si il n'y a aucune note
        if ($appreciations->isEmpty()) {
            foreach ($eleves as $eleve)
            {
                $appreciation = new Appreciation();
                $appreciation->setEleve($eleve)->setEnseignement($enseignement);
                $enseignement->addAppreciation($appreciation);
            }
        }

        $form = $this->createForm(AppreciationType::class, $enseignement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $enseignement = $form->getData();
            $appreciations = $enseignement->getAppreciation();

            foreach ($appreciations as $appreciation)
            {
                $entityManager->persist($appreciation);
            }
            $entityManager->flush();

            $this->addFlash('success', 'Appreciations ajoutés avec succès !');

            return $this->redirectToRoute('appreciation_index');
        }

        return $this->render('appreciation/commenter.html.twig', [
            'enseignement' => $enseignement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="appreciation_show", methods={"GET"})
     */
    public function show(Appreciation $appreciation): Response
    {
        return $this->render('appreciation/show.html.twig', [
            'appreciation' => $appreciation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="appreciation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Appreciation $appreciation): Response
    {
        $form = $this->createForm(AppreciationType::class, $appreciation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('appreciation_index');
        }

        return $this->render('appreciation/edit.html.twig', [
            'appreciation' => $appreciation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="appreciation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Appreciation $appreciation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$appreciation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($appreciation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('appreciation_index');
    }
}
