<?php

namespace FormArmorBundle\Controller;

use FormArmorBundle\Form\ClientType;
use FormArmorBundle\Form\ClientCompletType;
use FormArmorBundle\Form\StatutType;
use FormArmorBundle\Form\FormationType;
use FormArmorBundle\Form\SessionType;
use FormArmorBundle\Form\PlanFormationType;

use FormArmorBundle\Entity\Client;
use FormArmorBundle\Entity\Formation;
use FormArmorBundle\Entity\Session_formation;
use FormArmorBundle\Entity\Plan_formation;
use FormArmorBundle\Entity\Statut;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function authentifAction(Request $request) // Affichage du formulaire d'authentification
    {
        
		// Création du formulaire
		$client = new Client();
		$form   = $this->get('form.factory')->create(ClientType::class, $client);
		
		
		// Contrôle du mdp si method POST ou affichage du formulaire dans le cas contraire
		if ($request->getMethod() == 'POST')
		{
			$form->handleRequest($request); // permet de récupérer les valeurs des champs dans les inputs du formulaire.
			if ($form->isValid())
			{
				// Récupération des données saisies (le nom des controles sont du style nomDuFormulaire[nomDuChamp] (ex. : client[nom] pour le nom) )
				$donneePost = $request->request->get('client');
				$nom = $donneePost['nom'];
				$mdp = $donneePost['password'];
				
				// Controle du nom et du mdp
				$manager = $this->getDoctrine()->getManager();
				$rep = $manager->getRepository('FormArmorBundle:Client');
				$nbClient = $rep->verifMDP($nom, $mdp);
				if ($nbClient > 0)
				{
					return $this->render('FormArmorBundle:User:accueil.html.twig');
				}
				$request->getSession()->getFlashBag()->add('connection', 'Login ou mot de passe incorrects');
			}
		}
		
		// Si formulaire pas encore soumis ou pas valide (affichage du formulaire)
		return $this->render('FormArmorBundle:User:connection.html.twig', array('form' => $form->createView()));
    }
}
	