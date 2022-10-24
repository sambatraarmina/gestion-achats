<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Article_model extends CI_Model {

    private $_id,
            $_categorie,
            $_nom,
            $_prix,
            $_unite,
            $_description,
            $_datecrea,
            $_datemodif,
            $_table = 'tr_article';
    

    //PUBLIC FUNCTIONS

	public function __construct() 
    {
        parent::__construct();
    }

    public function getAllArticle($user = false)
    {
        $this->db->select('article_id, article_nom, article_prix, article_unite, article_categorie, article_fournisseur')
                    ->select('categorie_id, categorie_nom') 
                    ->select('fournisseur_id, fournisseur_nom')   
                    ->select('unitereference_nom as unitereference')
                    ->select('panierdetails_qte as qte, panierdetails_total as total')
                    ->join('tr_categorie', 'categorie_id = article_categorie', 'inner')
                    ->join('tr_unitereference', 'unitereference_id = article_unite', 'inner')
                    ->join('tr_fournisseur', 'fournisseur_id = article_fournisseur', 'inner');
        
        if($user){
            $this->db->join('t_panier', 'panier_status = "ENC" AND panier_user = "'.$user.'"', 'left')
                        ->join('t_panierdetails', 'panierdetails_article = article_id AND panierdetails_panier = panier_id', 'left');
        }
       
        $query = $this->db->get($this->_table);
        //echo $this->db->last_query();
        if($query->num_rows() > 0){
            return $query->result();
        }

        return false;
    }

    public function getPrixArticle($article){
        $query = $this->db->select('article_prix')
            ->where('article_id', $article)
            ->get($this->_table);

            if($query->num_rows() > 0){
                return $query->row()->article_prix;
            }
    
            return false;
    }
}

    