<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\PromoteAdminType;
use App\Form\PromoteEditorType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
      
          

    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    /**
     * @Route("/promote/{id<\d+>}", name="app_promote")
     * 
     */
    public function promoteToAdmin($id, Request $request)
    {
        $secret = "azertyA1";

        $form = $this->createForm(PromoteAdminType::class);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        $utilisateur = $em->getRepository(Utilisateur::class)->find($id);

        if (!$utilisateur) {

            $this->addFlash('erreur', 'l\'utilisateur n\'existe pas ! ');
        }
        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get("secret")->getData() != $secret) {

                throw $this->createNotFoundException("Vous n'avez pas le bon code, vous êtes un intrus !!!");
            }
            $utilisateur->setRoles(["ROLE_ADMIN"]);
            $em->persist($utilisateur);
            $em->flush();

            return $this->redirectToRoute('app_login');
        }
        return $this->render("security/promoteAdmin.html.twig", [
            "utilisateur" => $utilisateur,
            "form" => $form->createView()
        ]);
    }
    /**
     * @Route("/promoteEditor/{id<\d+>}", name="app_promoteEditor")
     * 
     */
    public function promoteToEditor($id, Request $request)
    {
        $secret = "azertyA2";

        $form = $this->createForm(PromoteEditorType::class);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        $utilisateur = $em->getRepository(Utilisateur::class)->find($id);

        if (!$utilisateur) {

            $this->addFlash('erreur', 'l\'utilisateur n\'existe pas ! ');
        }
        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get("secret")->getData() != $secret) {

                throw $this->createNotFoundException("Vous n'avez pas le bon code, vous êtes un intrus !!!");
            }
            $utilisateur->setRoles(["ROLE_EDITOR"]);
            $em->persist($utilisateur);
            $em->flush();

            return $this->redirectToRoute('app_login');
        }
        return $this->render("security/promoteEditor.html.twig", [
            "utilisateur" => $utilisateur,
            "form" => $form->createView()
        ]);
    }
}
