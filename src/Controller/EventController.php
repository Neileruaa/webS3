<?php
/**
 * Created by PhpStorm.
 * User: aurelien
 * Date: 21/09/18
 * Time: 09:00
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends Controller {

	public $evenements = [
		['id' => 1,'nom' => 'Symfony Conference', 'description' => 'présentation de la conférence sur Symfony ', 'date' => '2019-2-20', 'prix' => '10.5'],
		['id' => 2,'nom' => 'Laravel Conference', 'description' => 'présentation de la conférence sur Laravel ', 'date' => '2019-3-2', 'prix' => NULL],
		['id' => 3,'nom' => 'Django Conference', 'description' => 'présentation de la conférence sur Django ', 'date' => '2019-3-25', 'prix' => '20'],
		['id' => 4,'nom' => 'JAVA2EE Conference', 'description' => 'présentation de la conférence sur Java J2EE ', 'date' => '2019-4-2', 'prix' => '30'],
		['id' => 5,'nom' => 'Rails Conference', 'description' => 'présentation de la conférence sur Ruby on Rails ', 'date' => '2019-4-26', 'prix' => '12'],
	];

	public function __construct(RequestStack $requestStack){
//		$this->session = new Session();
//		//$this->session->clear();   // permet de vider l'objet
//		$this->session->start();
		$request = $requestStack->getCurrentRequest();
		$session=$request->getSession();
		if($session->has('evenements'))
			$this->evenements=$session->get('evenements');
//		dump($this->evenements);
	}

	/**
	 * @Route("/events", name="Event.showEvents")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function showEvent(Request $request) {
		$session = $request->getSession();
		$session->set("evenements", $this->evenements);
		dump($this->evenements);
		$dateAujourdhui= (new \DateTime)->format('d/m/Y H:i:s');
		return $this->render(
			'events/showEvents.html.twig', array('datetoday'=>$dateAujourdhui, 'events'=>$session->get('evenements'))
		);
	}

	/**
	 * @Route("/events/details/{id}", name="Event.detailsEvent", requirements={"id" = "\d+"})
	 */
	public function  detailsEvent(Request $request, $id) {
		$session = $request->getSession();
		$evenement = [];
		for($i = 0; $i < sizeof($this->evenements); $i++){
			if($this->evenements[$i]['id'] == $id){
				$evenement = ["id"=>$this->evenements[$i]['id'], "nom"=>$this->evenements[$i]['nom'], "description"=>$this->evenements[$i]['description'], "date"=>$this->evenements[$i]['date'],
					"prix"=>$this->evenements[$i]['prix']
				];
			}
		}
		return $this->render(
			'events/detailsEvent.html.twig', array('event'=>$evenement)
		);
	}

	/**
	 * @Route("/lastEvent", name="Event.lastEvent")
	 * @param Request $request
	 */
	public function lastEvent(Request $request) {
		$lastEvent = $this->evenements[sizeof($this->evenements)- 1];
		return $this->redirectToRoute('Event.detailsEvent', array('id'=>$lastEvent['id']));
	}

	/**
	 * @Route("/events/add", name="Event.addEvent")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function addEvent(Request $request) {
		return $this->render(
			'events/add.html.twig', array()
		);
	}

	/**
	 * @Route("/events/update/{id}", name="Event.updateEvent", requirements={"id" = "\d+"})
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function updateEvent(Request $request, $id) {
		$session = $request->getSession();
		$evenement = [];
		for($i = 0; $i < sizeof($this->evenements); $i++){
			if($this->evenements[$i]['id'] == $id){
				$evenement = ["id"=>$this->evenements[$i]['id'], "nom"=>$this->evenements[$i]['nom'], "description"=>$this->evenements[$i]['description'], "date"=>$this->evenements[$i]['date'],
					"prix"=>$this->evenements[$i]['prix']
				];
			}
		}
		$idEvent = $evenement['id'];
		$nameEvent = $evenement['nom'];
		$descriptionEvent = $evenement['description'];
		$dateEvent = $evenement['date'];
		$priceEvent = $evenement['prix'];


		return $this->render(
			'events/update.html.twig', array(
				'event'=>$evenement,
				'idEvent'=>$idEvent,
				'nameEvent'=>$nameEvent,
				'descriptionEvent'=>$descriptionEvent,
				'dateEvent'=> $dateEvent,
				'priceEvent'=>$priceEvent
			)
		);
	}

	/**
	 * @Route("/events/valid", name="Event.validAddEvent")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function validEvent(Request $request) {
		$session = $request->getSession();

		$nom=$request->request->get('nom');
		$description=$request->request->get('description');
		$date=$request->request->get('date');
		$prix=$request->request->get('prix');

		$nbrEvt=sizeof($this->evenements);
		if ($nbrEvt == 0){
			$lastId=0;
		}else{
			$lastId=$this->evenements[$nbrEvt-1]['id'];
		}
		$ligne=["id" => ($lastId+1),"nom" => $nom,"description" => $description,"date" => $date,"prix" => $prix];
		array_push($this->evenements,$ligne);
		$session->set('evenements', $this->evenements);
		$this->addFlash('notice','événement ajouté !');

		return $this->redirectToRoute('Event.showEvents');
	}

	/**
	 * @Route("/events/validUpdate", name="Event.validUpdateEvent")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function validUpdateEvent(Request $request) {
		$session = $request->getSession();

		$id=$request->request->get('id');
		$nom=$request->request->get('nom');
		$description=$request->request->get('description');
		$date=$request->request->get('date');
		$prix=$request->request->get('prix');

		$ligne=["id" => $id,"nom" => $nom,"description" => $description,"date" => $date,"prix" => $prix];


		foreach ($this->evenements as &$evenement){
			if ($evenement['id'] == $id){
				$evenement = $ligne;
			}
		}

		$session->set('evenements', $this->evenements);
		$this->addFlash('notice','événement modifié !');

		return $this->redirectToRoute('Event.showEvents');
	}

	/**
	 * @Route("/events/delete", name="Event.deleteEvent", methods={"DELETE"})
	 * @param Request $request
	 */
	public function deleteEvent(Request $request) {
		$session = $request->getSession();
		$id = $request->request->get('id');
		$tmp = [];
		for($i = 0; $i < sizeof($this->evenements); $i++){
			if($this->evenements[$i]['id'] != $id){
				array_push($tmp, ["id"=>$this->evenements[$i]['id'], "nom"=>$this->evenements[$i]['nom'], "description"=>$this->evenements[$i]['description'],
					"date"=>$this->evenements[$i]['date'],
					"prix"=>$this->evenements[$i]['prix']]);
			}
		}
		$this->evenements = $tmp;
		$session->set('evenements', $tmp);
		unset($tmp);
		$this->addFlash('notice','événement supprimé !');

		return $this->redirectToRoute('Event.showEvents');
	}
}