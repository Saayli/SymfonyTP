<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieSupprimerType;
use App\Form\CategorieType;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    /**
     * @Route ("/", name="app_categories")
     */
    public function index(\Doctrine\Persistence\ManagerRegistry $doctrine, Request $request): Response
    {
        //création du formulaire d'ajout
        $categorie = new Categorie(); //on crée une catégorie vide
        //on crée un formulaire à partir de la classe CategorieType et de notre object vide
        $form = $this->createForm(CategorieType::class, $categorie);

        //Gestion du retour du formulaire
        //on rajoute Request dans les paramètres comme dans le projet précédent
        $form -> handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //le handlerequest a rempli notre objet $categorie
            //qui n'est plus vide
            //pour sauvegarder, on va récupérer un entity Manager de doctrine
            //qui comme son nom l'indique gère les entités
            $em = $doctrine->getManager();
            //on lui dit de la ranger dans la BDD
            $em->persist($categorie);
            //générer l'insert
            $em->flush();
        }


        //pour aller chercher les catégories, je vais utiliserun repository
        // pour me servir de doctrine j'ajoute le paramètres $doctrine à la méthode
        $repo = $doctrine->getRepository(Categorie::class);
        $categories = $repo->findAll();

        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
            'formulaire'=> $form->createView()
        ]);
    }
    /**
     * @Route("/categorie/modifier/{id}", name="categorie_modifier")
     */
    public function modifierCategorie($id, \Doctrine\Persistence\ManagerRegistry $doctrine, Request $request){
        //récupérer la catégorie dans la BDD
        $categorie = $doctrine->getRepository(Categorie::class)->find($id);
        //si on a r trouvé -> 404
        if(!$categorie){
            throw $this->createNotFoundException("Aucune catégorie avec l'id $id");
        }
        $form = $this->createForm(CategorieType::class, $categorie);
        $form -> handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($categorie);
            $em->flush();
            //retour à l'accueil
            return $this->redirectToRoute("app_categories");
        }
        return $this->render('categorie/modifier.html.twig', [
            'categorie' => $categorie,
            'formulaire'=> $form->createView()
        ]);


    }

    /**
     * @Route("/categorie/supprimer/{id}", name="categorie_supprimer")
     */
    public function supprimerCategorie($id, \Doctrine\Persistence\ManagerRegistry $doctrine, Request $request){
        //récupérer la catégorie dans la BDD
        $categorie = $doctrine->getRepository(Categorie::class)->find($id);
        //si on a r trouvé -> 404
        if(!$categorie){
            throw $this->createNotFoundException("Aucune catégorie avec l'id $id");
        }
        $form = $this->createForm(CategorieSupprimerType::class, $categorie);
        $form -> handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            //on lui dit de la supprimer de la BDD
            $em->remove($categorie);
            $em->flush();

            //retour à l'accueil
            return $this->redirectToRoute("app_categories");
        }
        return $this->render('categorie/supprimer.html.twig', [
            'categorie' => $categorie,
            'formulaire'=> $form->createView()
        ]);


    }
}
