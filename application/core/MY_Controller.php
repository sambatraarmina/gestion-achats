<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public $_isAuthenticated = false,
            $_userRole,
            $_userId,
            $_fullname,
            $_userState,
            $_statusLib,
            $_top;

    public $_userInfos;

    public function __construct()
    {

        parent::__construct();
        $this->_userInfos = $this->session->userdata('user');
        if(!$this->_userInfos){
            
            redirect('auth/login');
        }
        $this->_isAuthenticated = true;
        $this->_userRole = $this->session->userdata('user')['role'];
        $this->_userId = $this->_userInfos['id'];
        $this->_fullname = $this->_userInfos['nom'] . ' ' . $this->_userInfos['prenom'];
        
        $this->getTopMenuInfos();
    }

    public function getTopMenuInfos()
    {
        $this->load->model('panier_model');
        $panierInfos = $this->panier_model->getUserPanierActif($this->_userId);
        
        $topInfos = [
            'panier' => $panierInfos,
            'username' => $this->_userInfos['prenom'],
            'isGestionAchat' => $this->_userInfos['isGestionAchat'] 
        ];

        $this->_top = $topInfos;
    }
    
    public function showCommandLib($code){
        if($code == PANIER_ENCOURS) return 'En cours';
        if($code == PANIER_ANNULE) return 'Annulée';
        if($code == PANIER_VALIDE) return 'Validée';
        if($code == PANIER_TERMINE) return 'Terminée';
    }

    
}