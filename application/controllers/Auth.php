<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

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
	public function login($error = [])
	{

		$this->load->view('auth/login-page', array('error' => $error));
	}

	public function verify()
	{
		
		$username = $this->input->post('tt_username');
		$pwd = $this->input->post('tt_pwd');
		
		if(empty($username) || empty($pwd))
		{
			$error = [
				'msg' => 'Login/mot de passe incorrecte'
			];
			redirect('auth/login');
		}
		//Appel API timetracking via curl
   
        /* Data */
        $data = [
            'username'=>$username, 
            'pwd'=>$pwd
        ];
		$data_string = http_build_query($data);

		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, URL_AUTH);
		curl_setopt($ch,CURLOPT_POST, count($data));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		$result = json_decode($result,true);
		if($result){
			if($result['err']){
				$error = [
					'msg' => 'Login/mot de passe incorrecte'
				];
				redirect('auth/login');
			}
			$infosUser = $result['datas'];
			//Enregistrement en session des données de l'utilisateur
			$this->setToSessionUserInfos($infosUser);
			
			if($this->session->userdata('user')){
				//Go to authenticated pages (dashboard)
				redirect('article/listArticle');
			}
		}
				
		
	}

	/**
	 * Deconnexion de l'utilisateur courant
	 * Suppression session 
	 * Redirection to auth/login
	 */
	public function doLogout()
	{
		session_destroy();
		//S'assurer que la session a bien été détruite par redirection vers une page sécurisé
		redirect('article/listArticle');
	}

	//PRIVATE FUNCTIONS
	private function setToSessionUserInfos($datas)
	{
		
		$user = [
			'id' => $datas['usr_id'],
			'nom' => $datas['usr_nom'],
			'prenom' => $datas['usr_prenom'],
			'username' => $datas['usr_username'],
			'matricule' => $datas['usr_matricule'],
			'initiale' => $datas['usr_initiale'],
			'email' => $datas['usr_email'],
			'role' => $datas['usr_role'],
			'isactif' => $datas['usr_actif'],
			'istech' => $datas['usersuppl_istech'],
			'isGestionAchat' => $datas['usersuppl_isGestionAchat'],
			'site' => $datas['site_libelle']
		];

		$this->session->set_userdata('user', $user);
	}

}
