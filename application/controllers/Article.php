<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Article extends MY_Controller {

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

	/**
	 * Afficher la liste des srticles dispos
	 * */
	public function listArticle()
	{
		//TEMP : initialiser l'user sur admin
		//$this->session->set_userdata('user_id', 1);
		//----------------------------------

		$userId = $this->_userId;
		$this->load->model('panier_model');
		$isAllowed = $this->panier_model->isUserAllowedToOrder($userId);
		if(!$isAllowed) redirect('commande/listCommande');
		//var_dump($this->_top);die;
		$header = ['pageTitle' => 'Articles'];
		$this->load->view('common/header',  $header);
		$this->load->view('common/top', $this->_top);
		$this->load->view('article/article', []);
		$this->load->view('common/footer', []);
	}

	 /**
     * Prendre la liste des articles 
     */
    public function getListArticle()
    {
		$userId = $this->_userId;

        $this->load->model('article_model');
        $listArticle = $this->article_model->getAllArticle($userId);
        if(!$listArticle) $listArticle = [];
        
        echo json_encode(array('data' => $listArticle));
    }

	/**
	 * Mise à jour du panier d'un user
	 */
	public function updateUserCart(){
		$userId = $this->_userId;

		$post = $this->input->post();
		if($post 
			&& isset($post['qty']) 
			&& isset($post['article'])){

				$qte = $post['qty'];
				$article = $post['article'];

				$this->load->model('article_model');
				$this->load->model('panier_model');
				//Verification du panier de l'user
				$panierEnc = $this->panier_model->getUserPanierActif($userId);
				//Si existence du panier, on met à jour les infos
				if($panierEnc){
					$this->load->model('panierdetails_model');
					$prixArticle = $this->article_model->getPrixArticle($article);
					$data = [
						'qte' => $qte,
						'article' => $article,
						'user' => $userId,
						'prixArticle' => $prixArticle,
						'totalArticle' => $prixArticle * $qte,
						'panier' => $panierEnc->panier_id
					];
					$this->panierdetails_model->updatePanierArticle($data);
					//Mise à jour du panier (total, totalmensuel, suppression panierdetails avec qte à 0 ...)
					$this->panier_model->updatePanierById($panierEnc->panier_id);
					$panier = $this->panier_model->getUserPanierActif($userId);

					echo json_encode(array('error' => false, 'data' => $panier)); die;

				}else{
					//Debut de transaction
					$this->db->trans_start();

					//Si aucun panier actif est associé à l'user, 
					//on doit initialiser un nouveau panier pour ce dernier
					$panierId = $this->panier_model->createPanier($userId);

					$prixArticle = $this->article_model->getPrixArticle($article);
					$dataPanier = [
						[
							'panierdetails_panier' => $panierId,
							'panierdetails_article' => $article,
							'panierdetails_qte' => $qte,
							'panierdetails_total' => round($prixArticle * $qte, 2),
							'panierdetails_datecrea' => date('Y-m-d H:i:s'),
							'panierdetails_datemodif' => date('Y-m-d H:i:s')
						]
					];
					$this->load->model('panierdetails_model');
					$isInsertDetails = $this->panierdetails_model->setPanier($dataPanier);
					
					//Mise à jour du panier (total, totalmensuel, ...)
					$this->panier_model->updatePanierById($panierId);

					$this->db->trans_complete();

					if ($this->db->trans_status() !== FALSE){
						$panier = $this->panier_model->getUserPanierActif($userId);
						echo json_encode(array('error' => false, 'data' => $panier)); die;
					}
				}

				echo json_encode(array('error' => true));
				
		}
	}


}