<?php

namespace App\Controller;

use App\Entity\AppreciationGenerale;
use App\Form\AppreciationGeneraleType;
use App\Repository\AppreciationGeneraleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/appreciation/generale")
 */
class AppreciationGeneraleController extends AbstractController
{
    /**
     * @Route("/", name="appreciation_generale_index", methods={"GET"})
     */
    public function index(AppreciationGeneraleRepository $appreciationGeneraleRepository): Response
    {
        return $this->render('appreciation_generale/index.html.twig', [
            'appreciation_generales' => $appreciationGeneraleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="appreciation_generale_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $appreciationGenerale = new AppreciationGenerale();
        $form = $this->createForm(AppreciationGeneraleType::class, $appreciationGenerale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($appreciationGenerale);
            $entityManager->flush();

            return $this->redirectToRoute('appreciation_generale_index');
        }

        return $this->render('appreciation_generale/new.html.twig', [
            'appreciation_generale' => $appreciationGenerale,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="appreciation_generale_show", methods={"GET"})
     */
    public function show(AppreciationGenerale $appreciationGenerale): Response
    {
        return $this->render('appreciation_generale/show.html.twig', [
            'appreciation_generale' => $appreciationGenerale,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="appreciation_generale_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, AppreciationGenerale $appreciationGenerale): Response
    {
        $form = $this->createForm(AppreciationGeneraleType::class, $appreciationGenerale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('appreciation_generale_index');
        }

        return $this->render('appreciation_generale/edit.html.twig', [
            'appreciation_generale' => $appreciationGenerale,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="appreciation_generale_delete", methods={"DELETE"})
     */
    public function delete(Request $request, AppreciationGenerale $appreciationGenerale): Response
    {
        if ($this->isCsrfTokenValid('delete'.$appreciationGenerale->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($appreciationGenerale);
            $entityManager->flush();
        }

        return $this->redirectToRoute('appreciation_generale_index');
    }
}
