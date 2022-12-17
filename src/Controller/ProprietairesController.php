<?php

namespace App\Controller;

use App\Entity\Proprietaires;
use App\Form\ProprietairesSupprimerType;
use App\Form\ProprietairesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ProprietairesController extends AbstractController
{
    #[Route('/proprietaires', name: 'app_proprietaires')]
    public function index(\Doctrine\Persistence\ManagerRegistry $doctrine, Request $request): Response
    {

        $proprietaire = new Proprietaires();
        $form = $this->createForm(ProprietairesType::class, $proprietaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($proprietaire);
            $em->flush();
        }

        $repo = $doctrine->getRepository(Proprietaires::class);
        $proprietaire = $repo->findAll();

        return $this->render('proprietaires/index.html.twig', [
            "proprietaires" => $proprietaire,
            "formulaire" => $form->createView()
        ]);

    }

    /**
     * @Route("/proprietaires/ajouter/", name="ajouter_proprietaire")
     */
    public function ajouterProprietaire(\Doctrine\Persistence\ManagerRegistry $doctrine, Request $request)
    {
        $proprietaire = new Proprietaires();
        $form = $this->createForm(ProprietairesType::class, $proprietaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($proprietaire);
            $em->flush();
            $repo = $doctrine->getRepository(Proprietaires::class);
            $proprietaire = $repo->findAll();

            return $this->redirectToRoute("app_proprietaires");
        }
        return $this->render('proprietaires/ajouter.html.twig', [
            'proprietaire' => $proprietaire,
            'formulaire' => $form->createView()
        ]);
    }

    /**
     * @Route("/proprietaires/modifierProprietaire/{id}", name="modifier_proprietaire")
     */
    public function modifierProprietaire($id, \Doctrine\Persistence\ManagerRegistry $doctrine, Request $request)
    {
        $proprietaire = $doctrine->getRepository(Proprietaires::class)->find($id);
        if (!$proprietaire) {
            throw $this->createNotFoundException("Aucun proprietaire avec l'id $id");
        }
        $form = $this->createForm(ProprietairesType::class, $proprietaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($proprietaire);
            $em->flush();

            return $this->redirectToRoute("app_proprietaires", ["id" => $proprietaire->getId()]);
        }

        return $this->render("proprietaires/modifierProprietaire.html.twig", [
            "proprietaire" => $proprietaire,
            "formulaire" => $form->createView()
        ]);
    }

    /**
     * @Route("/proprietaire/supprimer/{id}", name="proprietaire_supprimer")
     */
    public function supprimerProprietaire($id, \Doctrine\Persistence\ManagerRegistry $doctrine, Request $request)
    {
        $proprietaire = $doctrine->getRepository(Proprietaires::class)->find($id);
        if (!$proprietaire) {
            throw $this->createNotFoundException("Aucun proprietaire avec l'id $id");
        }
        $form = $this->createForm(ProprietairesSupprimerType::class, $proprietaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->remove($proprietaire);
            $em->flush();

            return $this->redirectToRoute("app_proprietaires");
        }

        return $this->render("proprietaires/supprimerProprietaire.html.twig", [
            "proprietaire" => $proprietaire,
            "formulaire" => $form->createView()
        ]);


    }
}