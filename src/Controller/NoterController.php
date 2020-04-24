<?php

namespace App\Controller;

use App\Entity\Eleve;
use App\Entity\Noter;
use App\Form\NoterType;
use App\Entity\Evaluation;
use App\Entity\Enseignement;
use App\Repository\NoterRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/noter")
 */
class NoterController extends AbstractController
{
    /**
     * @Route("/", name="noter_index", methods={"GET"})
     */
    public function index(NoterRepository $noterRepository): Response
    {
        return $this->render('noter/index.html.twig', [
            'noters' => $noterRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="noter_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $noter = new Noter();
        $form = $this->createForm(NoterType::class, $noter);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($noter);
            $entityManager->flush();

            return $this->redirectToRoute('noter_index');
        }

        return $this->render('noter/new.html.twig', [
            'noter' => $noter,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/newNoteFromEvaluation/{id}", name="note_from_evaluation", methods={"GET","POST"})
     */
    public function newNoteFromEvaluation(Request $request, Evaluation $evaluation)
    {
        //Todo Afficher les eleves en rapport avec l'evaluation pour pemettre l'ajout des notes  
        $noter = new Noter();

        $entityManager = $this->getDoctrine()->getManager();

        //Recuperation des eleves
        $classeId = $evaluation->getEnseignement()->getClasse()->getId();
        $eleve = $entityManager->getRepository(Eleve::class)->findby(['classe' => $classeId]);

        //formulaire
        $form = $this->createForm(NoterType::class, $noter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($noter);
            $entityManager->flush();

            return $this->redirectToRoute('noter_index');
        }

        return $this->render('noter/new.html.twig', [
            'noter' => $noter,
            'eleve' => $eleve, //TODO afficher les eleves
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="noter_show", methods={"GET"})
     */
    public function show(Noter $noter): Response
    {
        return $this->render('noter/show.html.twig', [
            'noter' => $noter,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="noter_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Noter $noter): Response
    {
        $form = $this->createForm(NoterType::class, $noter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('noter_index');
        }

        return $this->render('noter/edit.html.twig', [
            'noter' => $noter,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="noter_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Noter $noter): Response
    {
        if ($this->isCsrfTokenValid('delete'.$noter->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($noter);
            $entityManager->flush();
        }

        return $this->redirectToRoute('noter_index');
    }
}
