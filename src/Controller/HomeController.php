<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @property Session session
 */
class HomeController extends Controller{
	public $evenements = [
		['id' => 1,'nom' => 'Symfony Conference', 'description' => 'présentation de la conférence sur Symfony ', 'date' => '2019-2-20', 'prix' => '10.5'],
		['id' => 2,'nom' => 'Laravel Conference', 'description' => 'présentation de la conférence sur Laravel ', 'date' => '2019-3-2', 'prix' => NULL],
		['id' => 3,'nom' => 'Django Conference', 'description' => 'présentation de la conférence sur Django ', 'date' => '2019-3-25', 'prix' => '20'],
		['id' => 4,'nom' => 'JAVA2EE Conference', 'description' => 'présentation de la conférence sur Java J2EE ', 'date' => '2019-4-2', 'prix' => '30'],
		['id' => 5,'nom' => 'Rails Conference', 'description' => 'présentation de la conférence sur Ruby on Rails ', 'date' => '2019-4-26', 'prix' => '12'],
	];

//	public function __construct() {
//		$this->session = new Session();
//		$this->session->start();
//		if($this->session->has('evenements'))
//			$this->evenements=$this->session->get('evenements');
//	}

	/**
	 * @Route("/", name="home_page", methods={"GET"})
	 * @Route("/home", name="home_page2", methods={"GET"})
	 */
	public function index(Request $request) {
	//		if($session->has('evenements'))
	//			$this->evenements=$session->get('evenements');

//		$session = $this->$request->getSession(); // Get started session
//		if(!$session instanceof Session)
//			$session = new Session(); // if there is no session, start it

//		$value = $session->getId(); // get session id
//		$value = 'test';
		return $this->render(
			'home.html.twig', array('evenements'=>$this->evenements)
		);
	}

	/**
	 * @Route("/name", name="showName", methods={"GET", "POST"})
	 * @param Request $request
	 * @return Response
	 */
	public function showName(Request $request) {
		$nom = $request->query->get('nom', 'world (default en GET)'); // on précise la valeur par défault
		$nom2= $request->request->get('nom', 'word (default en POST)'); // $_POST
		return $this->render(
			'showName.html.twig', array('nom'=>$nom ,'nom2'=>$nom2)
		);
	}

//	/**
//	 * @Route("/twig", name='twig")
//	 * @param Request $request
//	 */
//	public function testTwig(Request $request) {
//		return $this->render(
//			'testTwig.html.twig', array('nom'=>$nom ,'nom2'=>$nom2)
//		);
//	}
}