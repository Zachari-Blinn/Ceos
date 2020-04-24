<?php

namespace App\Controller;

use App\Entity\Prof;
use App\Entity\User;
use App\Entity\Eleve;
use App\Form\UserProfType;
use App\Form\UserEleveType;
use App\Repository\UserRepository;
use App\Security\AppCustomAuthenticator;
use function Symfony\Component\String\u;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/eleve/new", name="user_eleve_new", methods={"GET","POST"})
     * @Route("/prof/new", name="user_prof_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, AppCustomAuthenticator $authenticator): Response
    {
        $user = new User();

        $currentRoute = $request->attributes->get('_route');

        if($currentRoute == "user_eleve_new")
        {
            $form = $this->createForm(UserProfType::class, $user, [
                'userType' => "eleve",
            ]);
        }
        else
        {
            $form = $this->createForm(UserProfType::class, $user, [
                'userType' => "prof",
            ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            // Creation du Username
            $newUsername = $this->newUsername($nom = $user->getNom(), $prenom  = $user->getPrenom());
            $user->setUsername($newUsername);

            if($currentRoute == "user_eleve_new")
            {
                $user->getEleve()->setSlug($newUsername);
                $entityManager->persist($user->getEleve());
                $user->setRoles(['ROLE_ELEVE']);

            }
            else
            {
                $user->getProf()->setSlug($newUsername);
                $entityManager->persist($user->getProf());
                $user->setRoles(['ROLE_PROF']);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $userType = "prof";
        $form = $this->createForm(UserProfType::class, $user, [
            'userType' => $userType,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    // Genere un mot de passe aléatoire
    public function randomPassword($length = 21, $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $charactersLength = strlen($characters);
        $randomPassword = '';

        for ($i = 0; $i < $length; $i++) {
            $randomPassword .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomPassword;
    }

    // Genere nom d'utilisateur
    public function newUsername(String $nom, String $prenom)
    {
        $slugger = new AsciiSlugger();
        $entityManager = $this->getDoctrine()->getManager();

        $slug = $slugger->slug($prenom ." ". $nom);

        $firstLetter = $prenom[0];
        $username = u(u($firstLetter)->lower())->append(u($nom)->lower());
        $number = 0;

        while($entityManager->getRepository(User::class)->findBy(['username' => $username]) != null)
        {
            $number += 1;
            $username = $slugger->slug($username . $number);   
        }

        return $username;
    }

    /**
     * @Route("/admin", name="user_admin", methods={"GET","POST"})
     */
    public function giveAdminRole(): Response
    {
        $user = $this->getUser();

        $user->setRoles(["ROLE_ADMIN"]);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }
}
