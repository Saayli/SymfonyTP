<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Proprietaires;
use App\Form\CategorieSupprimerType;
use App\Form\CategorieType;
use App\Entity\Chaton;
use App\Form\ChatonSupprimerType;
use App\Form\ChatonType;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatonsController extends AbstractController
{
    #[Route('/chatons', name: 'app_chatons')]
    public function index(): Response
    {
        return $this->render('chatons/index.html.twig', [
            'controller_name' => 'ChatonsController',
        ]);
    }

    /**
     * @Route("/chaton/ajouter/", name="ajouter_chaton")
     */
    public function ajouterChaton(\Doctrine\Persistence\ManagerRegistry $doctrine, Request $request)
    {
        $chaton = new Chaton();
        $form = $this->createForm(ChatonType::class, $chaton);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($chaton);
            $em->flush();
            $repo = $doctrine->getRepository(Chaton::class);
            $chaton = $repo->findAll();
            return $this->redirectToRoute("app_categories");
        }
        return $this->render('chatons/ajouter.html.twig', [
            'chaton' => $chaton,
            'formulaire' => $form->createView()
        ]);
    }

    /**
     * @Route("/chaton/{id}", name="chaton_afficher")
     */
    public function afficherChatons($id, \Doctrine\Persistence\ManagerRegistry $doctrine, Request $request)
    {
        //récupérer la catégorie dans la BDD
        $categorie = $doctrine->getRepository(Categorie::class)->find($id);
        $proprio = $doctrine->getRepository(Proprietaires::class)->find($id);
        if (!$categorie) {
            throw $this->createNotFoundException("Aucune catégorie avec l'id $id");
        }
        $chatons = $categorie->getChatons();
        return $this->render('chatons/afficher.html.twig', [
            'categorie' => $categorie,
            'chatons' => $chatons,
            'proprietaires' => $proprio
        ]);


    }

    /**
     * @Route("/chaton/modifier/{id}", name="chaton_modifier")
     */
    public function modifierChaton($id, \Doctrine\Persistence\ManagerRegistry $doctrine, Request $request)
    {
        //récupérer la catégorie dans la BDD
        $chaton = $doctrine->getRepository(Chaton::class)->find($id);
        //si on a r trouvé -> 404
        if (!$chaton) {
            throw $this->createNotFoundException("Aucun chat avec l'id $id");
        }
        $form = $this->createForm(ChatonType::class, $chaton);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($chaton);
            $em->flush();
            //retour à l'accueil
            return $this->redirectToRoute("app_categories");
        }
        return $this->render('chatons/modifierChaton.html.twig', [
            'chaton' => $chaton,
            'formulaire' => $form->createView()
        ]);
    }

    /**
     * @Route("/chaton/supprimer/{id}", name="chaton_supprimer")
     */
    public function supprimerChaton($id, \Doctrine\Persistence\ManagerRegistry $doctrine, Request $request)
    {
        $chaton = $doctrine->getRepository(Chaton::class)->find($id);
        $categorie = $chaton->getCategorie();
        $categorieId = $categorie->getId();
        if (!$chaton) {
            throw $this->createNotFoundException("Aucun chaton avec l'id $id");
        }
        $form = $this->createForm(ChatonSupprimerType::class, $chaton);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->remove($chaton);
            $em->flush();

            //retour à l'accueil
            return $this->redirectToRoute("app_categories");
        }
        return $this->render('chatons/supprimerChaton.html.twig', [
            'chaton' => $chaton,
            'categorie' => $categorieId,
            'formulaire' => $form->createView()
        ]);
    }

    /**
     * @Route("/categorie/chatonsProprietaireAfficher/{id}", name="chatons_proprietaire_afficher")
     */
    public function chatonsProprietaireAfficher($id, \Doctrine\Persistence\ManagerRegistry $doctrine, Request $request)
    {
        $proprietaire = $doctrine->getRepository(Proprietaires::class)->find($id);
        $chatons = $proprietaire->getChatId();
        if (!$proprietaire) {
            throw $this->createNotFoundException("Aucun propriétaire avec l'id $id");
        }

        return $this->render("chatons/afficherChatonsProprietaire.html.twig", [
            "proprietaire" => $proprietaire,
            "chatons" => $chatons
        ]);


    }
}
