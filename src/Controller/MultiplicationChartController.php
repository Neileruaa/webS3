<?php
/**
 * Created by PhpStorm.
 * User: aurelien
 * Date: 16/09/18
 * Time: 17:22
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MultiplicationChartController extends Controller {
	const NOMBRELIENS = 9;

	/**
	 * @Route("/Multiplication", name="home_multiplication")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function index(Request $request) {

		$numberPost = $request->request->get('chiffre', 'default post');
		if ($numberPost != 'default post'){
			if (is_numeric($numberPost)) {
				$number = $numberPost;
			}else{
				$number = 0;
				$this->addFlash('error', 'You didnt submit a number');
			}
		} else {
			$numberGet = $request->query->get('number');
			$number = $numberGet;
		}


		return $this->render(
			'Multiplication/Multiplication.html.twig',
			array(
				'nbliens'=> self::NOMBRELIENS,
				'number'=>$number
			)
		);
	}
}