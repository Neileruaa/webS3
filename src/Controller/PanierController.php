<?php
/**
 * Created by PhpStorm.
 * User: aurelien
 * Date: 23/09/18
 * Time: 17:22
 */

namespace App\Controller;


use App\Entity\Ticket;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends Controller {

	public $monPanier = [
		["id"=>0, "nom"=>'article1',"prix"=>1, "quantité"=>2, "prix_total"=>2]
	];

	public function __construct(RequestStack $requestStack){
		$request = $requestStack->getCurrentRequest();
		$session=$request->getSession();
		if($session->has('monPanier'))
			$this->monPanier=$session->get('monPanier');
	}

	/**
	 * @Route("/panier", name="home_panier")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function indexPanier(Request $request) {
		$session = $request->getSession();
		$session->set('monPanier', $this->monPanier);
		$panierEnSession = $session->get('monPanier');
		return $this->render(
			'Panier/homePanier.html.twig'   ,
			array('panier'=>$panierEnSession)
		);
	}

	/**
	 * @Route("/viderPanier", name="vider_panier")
	 * @param Request $request
	 */
	public function viderPanier(Request $request) {
		$session = $request->getSession();
		$session->set('monPanier', []);
		$this->monPanier = $session->get('monPanier');

		return $this->redirectToRoute('home_panier');
	}

	/**
	 * @Route("/panier/tickets", name="show_tickets")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function showTickets(Request $request) {
		$tickets = $this->getDoctrine()
			->getRepository(Ticket::class)
			->findAll();

		if (!$tickets){
			throw $this->createNotFoundException(
				'Pas de tickets trouvé'
			);
		}
		return $this->render(
			'Panier/showTickets.html.twig',
			array('tickets'=>$tickets)
		);
	}

	/**
	 * @Route("/panier/generateRandomTicket", name="generateRandomTicket")
	 * @param Request $request
	 */
	public function createRandomTicket(Request $request) {
		$entityManager = $this->getDoctrine()->getManager();

		$ticket = new Ticket();
		$ticket->setName('randomName'.rand(0,50));
		$ticket->setPrice(rand(0,100));

		$entityManager->persist($ticket);

		$entityManager->flush();

		return $this->redirectToRoute('show_tickets');
	}

	/**
	 * @Route("panier/addElement/{id}", name="addTicketToPanier", requirements={"id" = "\d+"})
	 * @param Request $request
	 */
	public function addTicketToPanier(Request $request, $id) {

	}

	function searchProduit($idProduit){
		for($i = 0; $i < sizeof($this->monPanier); $i++){
			if($idProduit == $this->monPanier[$i]['id']){
				return true;
				break;
			}
		}
		return false;
	}

	function infoProduit($idProduit){
		for($i = 0; $i < sizeof($this->evenements); $i++){
			if($idProduit == $this->evenements[$i]['id']){
				return $this->evenements[$i];
				break;
			}
		}
		return false;
	}
}