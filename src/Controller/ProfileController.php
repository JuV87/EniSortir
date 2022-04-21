<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Form\RecoveryFormType;
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
            $user->setRoles(["ROLE_USER"]);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

        }

        return $this->render('registration/profile.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

// Détails de l'utilisateur
    /**
     * @Route("/details", name="details")
     */
    public function details( ): Response
    {
        $user = $this->getUser();
        return $this->render('registration/detailsprofile.html.twig', [
            'user' => $user
        ]);
    }


//Modification de l'utilisateur
    /**
     * @Route("/modify/", name="modify_profile")
     */
    public function modify( Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('oops, pas dans la base de données!');
        }
        $recoveryForm = $this->createForm(RecoveryFormType::class, $user);
        $recoveryForm->handleRequest($request);

        if ($recoveryForm->isSubmitted() && $recoveryForm->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $recoveryForm['photo']->getData();
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
            $this->addFlash('success', "Votre mot de passe a bien été modifié");
            return $this->redirectToRoute('details');
        }
        return $this->render("registration/modifyprofile.html.twig", ['recoveryForm' => $recoveryForm->createView()]);
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
            $this->addFlash('success', "Votre profil a bien été modifié");
            return $this->redirectToRoute('details');
        }
        return $this->render("registration/changepassword.html.twig", ['changePasswordForm' => $changePasswordForm->createView()]);
    }
}
