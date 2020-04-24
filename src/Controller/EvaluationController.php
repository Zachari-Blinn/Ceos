<?php

namespace App\Controller;

use App\Entity\Noter;
use App\Entity\Classe;
use App\Entity\Matiere;
use App\Entity\Evaluation;
use App\Form\EvaluationType;
use App\Form\EvaluationNoterType;
use App\Repository\EleveRepository;
use App\Repository\EnseignementRepository;
use App\Repository\EvaluationRepository;
use App\Repository\NoterRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/evaluation")
 */
class EvaluationController extends AbstractController
{
    /**
     * @IsGranted("ROLE_PROF")
     * @Route("/", name="evaluation_index", methods={"GET"})
     */
    public function index(EvaluationRepository $evaluationRepository, EnseignementRepository $enseignementRepository): Response
    {
        if ($this->isGranted('ROLE_ADMIN'))
        {
            $enseignement = $enseignementRepository->findAll();
        }
        else
        {
            $currentUser = $this->getUser()->getProf()->getId();
            $enseignement = $enseignementRepository->findBy(['prof' => $currentUser]);
        }

        return $this->render('evaluation/index.html.twig', [
            'evaluations' => $evaluationRepository->findby(['enseignement' => $enseignement]),
        ]);
    }

    /**
     * @IsGranted("ROLE_PROF")
     * @Route("/{classe}/{matiere}/new", name="evaluation_new", methods={"GET","POST"})
     */
    public function new(Classe $classe, Matiere $matiere, Request $request, EnseignementRepository $enseignementRepository): Response
    {
        $currentUser = $this->getUser()->getProf()->getId();

        $enseignement = $enseignementRepository->findOneBy([
            'prof' => $currentUser,
            'classe' => $classe,
            'matiere' => $matiere
        ]);

        if($enseignement == null)
        {
            throw $this->createAccessDeniedException("ERREUR");
        }

        $evaluation = new Evaluation();
        $form = $this->createForm(EvaluationType::class, $evaluation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();

            $slugger = new AsciiSlugger();

            $nom = $evaluation->getLibelle();

            $slug = $slugger->slug($nom);
            $evaluation->setSlug($slug);

            $evaluation->setEnseignement($enseignement);

            $entityManager->persist($evaluation);
            $entityManager->flush();

            $this->addFlash('success', 'Nouvelle évaluation créé !');

            return $this->redirectToRoute('evaluation_index');
        }

        return $this->render('evaluation/new.html.twig', [
            'classe' => $classe->getId(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_PROF")
     * @Route("/{id}/edit", name="evaluation_edit", methods={"GET","POST"})
     */
    public function edit(Evaluation $evaluation, Request $request): Response
    {
        $this->denyAccessUnlessGranted('EDIT', $evaluation);

        $form = $this->createForm(EvaluationType::class, $evaluation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Evaluation éditer avec succès !');

            return $this->redirectToRoute('evaluation_index');
        }

        return $this->render('evaluation/edit.html.twig', [
            'evaluation' => $evaluation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_PROF")
     * @Route("/{id}", name="evaluation_delete", methods={"DELETE"})
     */
    public function delete(Evaluation $evaluation, Request $request): Response
    {
        $this->denyAccessUnlessGranted('DELETE', $evaluation);

        if ($this->isCsrfTokenValid('delete'.$evaluation->getId(), $request->request->get('_token')))
        {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->remove($evaluation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('evaluation_index');
    }

    /**
     * @Route("/{id}/notes", name="evaluation_notes", methods={"GET","POST"})
     */
    public function notes(Evaluation $evaluation, NoterRepository $noterRepository): Response
    {
        $this->denyAccessUnlessGranted('VIEW', $evaluation);

        $notes = $noterRepository->findby(['Evaluation' => $evaluation]);

        return $this->render('evaluation/notes.html.twig', [
            'notes' => $notes,
            'evaluation' => $evaluation,
        ]);
    }

    /**
     * @IsGranted("ROLE_PROF")
     * @Route("/classe", name="evaluation_classe", methods={"GET"})
     */
    public function selectClasse(EnseignementRepository $enseignementRepository): Response
    {
        $currentUser = $this->getUser()->getProf()->getId();

        $classes = $enseignementRepository->findGroupBy($currentUser);

        return $this->render('evaluation/classe.html.twig', [
            'classes' => $classes,
        ]);
    }

    /**
     * @IsGranted("ROLE_PROF")
     * @Route("/{classe}/matiere", name="evaluation_selectMatiere", methods={"GET"})
     */
    public function selectMatiere(Classe $classe, EnseignementRepository $enseignementRepository): Response
    {
        $currentUser = $this->getUser()->getProf()->getId();

        $matieres = $enseignementRepository->findBy(['prof' => $currentUser, 'classe' => $classe->getId()]);

        return $this->render('evaluation/matiere.html.twig', [
            'matieres' => $matieres,
            'classe' => $classe->getId(),
        ]);
    }

    /**
     * @IsGranted("ROLE_PROF")
     * @Route("/{id}/noter", name="evaluation_noter", methods={"GET","POST"})
     */
    public function noterEleve(Evaluation $evaluation, Request $request, EleveRepository $eleveRepository)
    {
        if (!$evaluation) return $this->redirectToRoute('evaluation_index');

        $entityManager = $this->getDoctrine()->getManager();

        // On récupère les notes de l'évaluation
        $noters = $evaluation->getNoters();

        // On récupère tous les élèves
        $classeId = $evaluation->getEnseignement()->getClasse()->getId();
        $eleves = $eleveRepository->findBy(['classe' => $classeId]);

        // Si il n'y a aucune note
        if ($noters->isEmpty()) {

            foreach ($eleves as $eleve) {

                $noter = new Noter();
                $noter->setEleve($eleve)
                      ->setEvaluation($evaluation);

                $evaluation->addNoter($noter);
            }
        }

        $form = $this->createForm(EvaluationNoterType::class, $evaluation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {

            $evaluation = $form->getData();
            $noters = $evaluation->getNoters();

            foreach ($noters as $noter) {

                $entityManager->persist($noter);

            }

            $entityManager->flush();

            return $this->redirectToRoute('evaluation_index');
        }

        return $this->render('evaluation/noter.html.twig', [
            'evaluation' => $evaluation,
            'form' => $form->createView(),
        ]);
    }
}
