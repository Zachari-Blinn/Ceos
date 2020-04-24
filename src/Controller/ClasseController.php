<?php

namespace App\Controller;

use App\Entity\Eleve;
use App\Entity\Classe;
use App\Form\ClasseType;
use App\Repository\ClasseRepository;
use App\Repository\EleveRepository;
use App\Repository\EnseignementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/classe")
 */
class ClasseController extends AbstractController
{
    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_PROF')")
     * @Route("/", name="classe_index", methods={"GET"})
     */
    public function index(ClasseRepository $classeRepository, EnseignementRepository $enseignementRepository): Response
    {
        if ($this->isGranted('ROLE_ADMIN'))
        {
            $classes = $classeRepository->findAll();
        }
        else
        {
            $currentUser = $this->getUser()->getProf()->getId();
            $classes = $enseignementRepository->findBy(['prof' => $currentUser]);
        }

        return $this->render('classe/index.html.twig', [
            'classes' => $classes,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/new", name="classe_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $classe = new Classe();
        $form = $this->createForm(ClasseType::class, $classe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();

            $slugger = new AsciiSlugger();

            $classeLibelle = $classe->getLibelle();

            $slug = $slugger->slug($classeLibelle);
            $classe->setSlug($slug);

            $entityManager->persist($classe);
            $entityManager->flush();

            return $this->redirectToRoute('classe_index');
        }

        return $this->render('classe/new.html.twig', [
            'classe' => $classe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}/edit", name="classe_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Classe $classe): Response
    {
        $form = $this->createForm(ClasseType::class, $classe, [
            'annee' => $classe->getAnnee(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('classe_index');
        }

        return $this->render('classe/edit.html.twig', [
            'classe' => $classe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}", name="classe_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Classe $classe, EleveRepository $eleveRepository, EnseignementRepository $enseignementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$classe->getId(), $request->request->get('_token')))
        {
            $eleves = $eleveRepository->findBy(['classe' => $classe->getId()]);
            $enseignements = $enseignementRepository->findBy(['classe' => $classe->getId()]);

            if($enseignements != null)
            {
                $this->addFlash('danger', 'Erreur - suppression impossible : La classe possède un ou des enseignement(s) !');
            }
            elseif($eleves != null)
            {
                $this->addFlash('danger', 'Erreur - suppression impossible : La classe possède un ou des élève(s) !');
            }
            else
            {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($classe);
                $entityManager->flush();
            }
        }

        return $this->redirectToRoute('classe_index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_PROF')")
     * @Route("/{id}/eleve", name="classe_eleve", methods={"GET","POST"})
     */
    public function eleve(Classe $classe, Request $request, EnseignementRepository $enseignementRepository): Response
    {
        $enseignement = $enseignementRepository->findBy(['classe' => $classe]);
        $currentUser = $this->getUser()->getProf();
        $result = false;

        // foreach($enseignement as $enseignement)
        // {
        //     $prof = $enseignement->getProf();
        //     if($prof->getId() == $currentUser->getId())
        //     {
        //         $result = true;
        //     }
        //     if($this->isGranted('ROLE_ADMIN'))
        //     {
        //         $result = true;
        //     }
        // }
        // if($result != true)
        // {
        //     throw new NotFoundHttpException('Sorry not existing!');
        // }

        $entityManager = $this->getDoctrine()->getManager();

        $eleveFromClasse = $entityManager->getRepository(Eleve::class)->findby(['classe' => $classe->getId()]);

        return $this->render('classe/eleve.html.twig', [
            'eleveFromClasse' => $eleveFromClasse, 
        ]);
    }
}
