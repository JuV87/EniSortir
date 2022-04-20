<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\AppAuthentificatorAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProfileController extends AbstractController
{

//Inscription d'un utilisateur
    /**
     * @Route("/profile", name="app_profile")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, UserAuthenticatorInterface $userAuthenticator, AppAuthentificatorAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email


            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );

        }

        return $this->render('registration/profile.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

// Détails de l'utilisateur
    /**
     * @Route("/details/{id}", name="details")
     */
    public function details($id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);
        return $this->render('registration/detailsprofile.html.twig', [
            'user' => $user
        ]);
    }


//Modification de l'utilisateur
    /**
     * @Route("/modify/{id}", name="modify_profile")
     */
    public function modify($id, Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $user = $userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('oops, pas dans la base de données!');
        }
        $registrationForm = $this->createForm(RegistrationFormType::class, $user);
        $registrationForm->handleRequest($request);

        if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $registrationForm['photo']->getData();
            if ($uploadedFile) {
                $destination = $this->getParameter('kernel.project_dir').'/public/uploads/article_image';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = uniqid().'.'.$uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $user->setPhoto($newFilename);
            }
            $entityManager->persist($user);
            $entityManager->flush();
            //               $this->addFlash('success', "Votre produit a bien été modifié");
            //               return $this->redirectToRoute('main_home');
        }
        return $this->render("registration/modifyprofile.html.twig", ['registrationForm' => $registrationForm->createView()]);
    }

    //Modification du mot de passe
    /**
     * @Route("/changepassword", name="modify_password")
     */
    public function change_password( Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('oops, pas dans la base de données!');
        }
        $changePasswordForm = $this->createForm( ChangePasswordFormType::class, $user);
        $changePasswordForm->handleRequest($request);
        if ($changePasswordForm->isSubmitted() && $changePasswordForm->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $changePasswordForm->get('plainPassword')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();
            //               $this->addFlash('success', "Votre produit a bien été modifié");
            //               return $this->redirectToRoute('main_home');
        }
        return $this->render("registration/changepassword.html.twig", ['changePasswordForm' => $changePasswordForm->createView()]);
    }
}
