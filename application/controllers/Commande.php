<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Commande extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */

	public function listCommande(){

		$userId = $this->_userId;
		
		$this->load->model('panier_model');

		$commandes = $this->panier_model->getListCommandeUser($userId);
		//var_dump($panier, $panierDetails); die;
		$header = ['pageTitle' => 'Mes commandes'];
		$this->load->view('common/header',  $header);
		$this->load->view('common/top', $this->_top);
		$this->load->view('commande/listCommande', ['commandes' => $commandes]);
	}

	public function gestionCommande(){
		//Redirection sur liste des commandes si non autorisé
		if(!$this->_top['isGestionAchat']) redirect('commande/listCommande');

		$this->load->model('panier_model');

		$commandes = $this->panier_model->getListCommande();
		//var_dump($panier, $panierDetails); die;
		$header = ['pageTitle' => 'Gestion des commandes'];
		$this->load->view('common/header',  $header);
		$this->load->view('common/top', $this->_top);
		$this->load->view('commande/gestionCommande', ['commandes' => $commandes]);
	}

	public function getListCommande(){
		$this->load->model('panierdetails_model');
		$commandes = $this->panierdetails_model->getPanierDetails();
		if(!$commandes) $commandes = [];

		echo json_encode(['data' => $commandes]);
	}

	public function panier()
	{

		$userId = $this->_userId;
		
		$this->load->model('panier_model');
		$this->load->model('panierdetails_model');

		/*$isAllowed = $this->panier_model->isUserAllowedToOrder($userId);
		if(!$isAllowed) redirect('commande/listCommande');*/

		$panier = $this->panier_model->getUserPanierActif($userId);
		$panierDetails = $this->panierdetails_model->getPanierDetailsByUser($userId);
		//var_dump($this->_userInfos); die;
		$header = ['pageTitle' => 'Mon panier'];
		$this->load->view('common/header',  $header);
		$this->load->view('common/top', $this->_top);
		$this->load->view('commande/Commande', ['panier' => $panier, 'panierDetails' => $panierDetails, 'userInfos' => $this->_userInfos]);
	}

	public function recapitulatif(){

		$userId = $this->_userId;
		
		$this->load->model('panier_model');
		$this->load->model('panierdetails_model');

		$isAllowed = $this->panier_model->isUserAllowedToOrder($userId);
		if(!$isAllowed) redirect('commande/listCommande');

		$panier = $this->panier_model->getUserPanierActif($userId);
		$panierDetails = $this->panierdetails_model->getPanierDetailsByUser($userId);
		
		$header = ['pageTitle' => 'Recapitulatif'];
		$this->load->view('common/header',  $header);
		$this->load->view('common/top', $this->_top);
		$this->load->view('commande/recapitulatif', ['panier' => $panier, 'panierDetails' => $panierDetails, 'userInfos' => $this->_userInfos]);
	}

	public function updateCartArticle(){
		$post = $this->input->post();
		if($post){

			$this->load->model('article_model');
			$this->load->model('panier_model');
			$this->load->model('panierdetails_model');

			$article = $post['article'];
			$panier = $post['panier'];
			$qte = $post['qte'];
			$userId = $this->_userId;
			$prixArticle = $this->article_model->getPrixArticle($article);

			$data = [
				'qte' => $qte,
				'article' => $article,
				'user' => $userId,
				'prixArticle' => $prixArticle,
				'totalArticle' => $prixArticle * $qte,
				'totalArticleFormatted' => number_format($prixArticle * $qte, 2, ',', ' ') . ' Ar',
				'panier' => $panier
			];
			$this->panierdetails_model->updatePanierArticle($data);
			//Mise à jour du panier (total, totalmensuel, suppression panierdetails avec qte à 0 ...)
			$this->panier_model->updatePanierById($panier);
			$panierInfos = $this->panier_model->getPanierById($panier);

			$data = [
				'qte' => $qte,
				'article' => $article,
				'prixArticle' => number_format($prixArticle, 2, ',', ' ') . ' Ar',
				'totalArticle' => number_format($prixArticle * $qte, 2, ',', ' ') . ' Ar',
				'panier' => $panierInfos
			];

			echo json_encode(['error' => false, 'data' => $data]); die;
		}
		echo json_encode(['error' => true]); die;
	}

	public function deleteLigneArticle(){
		$post = $this->input->post();
		if($post){
			$panierdetailsId = $post['ligne'];
			$panierId = $post['panier'];

			if(!$panierdetailsId){
				echo json_encode(['error' => true]); die;
			}

			$this->load->model('panierdetails_model');
			$this->panierdetails_model->deleteLigne($panierdetailsId);
			$this->load->model('panier_model');
			$this->panier_model->updatePanierById($panierId);
			$panierInfos = $this->panier_model->getPanierById($panierId);

			echo json_encode(['error' => false, 'data' => $panierInfos]); die; 

		}
	}

	public function deleteCart(){
		$post = $this->input->post();
		if($post){
			$panier_id = $post['panier'];
			$user_id = $this->_userId;
			$this->load->model('panier_model');
			if($this->panier_model->cancelPanier($panier_id, $user_id)){
				echo json_encode(['error' => false]); die;
			}

			echo json_encode(['error' => true]);
		}
	}

	public function validateCart(){
		$post = $this->input->post();
		if($post){
			$panier_id = $post['panier'];
			$user_id = $this->_userId;
			$this->load->model('panier_model');
			if($this->panier_model->validatePanier($panier_id, $user_id)){
				echo json_encode(['error' => false]); die;
			}

			echo json_encode(['error' => true]);
		}
	}
}
