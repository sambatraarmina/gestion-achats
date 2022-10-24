<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Panierdetails_model extends CI_Model {

    private $_id,
            $_panier,
            $_article,
            $_prix,
            $_qte,
            $_total,
            $_datecrea,
            $_datemodif,
            $_table = 't_panierdetails';
    
    //PUBLIC FUNCTIONS

	public function __construct() 
    {
        parent::__construct();
    }

    public function updatePanierArticle($data){
        $qte = $data['qte'];
        $panier = $data['panier'];
        $article = $data['article'];
        $prixArticle = $data['prixArticle'];
        $totalArticle = $data['totalArticle'];

        if($qte == '0'){
            $this->db->where('panierdetails_article', $article)
                    ->where('panierdetails_panier', $panier)
                    ->delete($this->_table);
            if($this->db->affected_rows() > 0) return true;
            return false;
        }

        $this->db->set('panierdetails_qte', $qte)
                    ->set('panierdetails_prix', $prixArticle)
                    ->set('panierdetails_total', $totalArticle)
                    ->where('panierdetails_panier', $panier)
                    ->where('panierdetails_article', $article)
                    ->update($this->_table);

        
        
        //On retourne si une mise à jour a été fait, sinon on appelle un insert
        if($this->db->affected_rows() > 0) return true;

        $this->db->set('panierdetails_panier', $panier)
                    ->set('panierdetails_article', $article)
                    ->set('panierdetails_qte', $qte)
                    ->set('panierdetails_prix', $prixArticle)
                    ->set('panierdetails_total', $prixArticle * $qte)
                    ->set('panierdetails_datecrea', 'NOW()', false)
                    ->set('panierdetails_datemodif', 'NOW()', false)
                    ->insert($this->_table);
        
        if($this->db->affected_rows() > 0){
            return $this->db->insert_id(); 
        }else{
            return false;
        }
    }

    public function setPanier($panierDetails){
        
        if($this->db->insert_batch($this->_table, $panierDetails)) return true;

        return false;
    }


    public function getPanierDetailsByUser($user, $status = PANIER_ENCOURS)
    {
        $this->db->select('panierdetails_id, panierdetails_panier, panierdetails_article, panierdetails_prix, panierdetails_qte, panierdetails_total')
                ->select('panier_id, panier_status')
                ->select('article_id, article_nom')
                //->select('usr_id')
                ->select('categorie_nom')
                ->from($this->_table)
                ->join('t_panier', 'panier_id = panierdetails_panier', 'inner')
                ->join('tr_article', 'article_id = panierdetails_article', 'inner')
                //->join('tr_user', 'usr_id = panier_id', 'inner')
                ->join('tr_categorie', 'categorie_id = article_categorie', 'inner')
                ->where('panier_user', $user)
                ->where('panier_status', $status)
                ->order_by('panierdetails_id ASC');
        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    public function deleteLigne($id){
        $this->db->where('panierdetails_id', $id)
            ->delete($this->_table);
        if($this->db->affected_rows() > 0) return true;
        return false;
    }

    /*public function getPanierDetails()
    {
        $this->db->select('panierdetails_id, panierdetails_panier, panierdetails_article, panierdetails_prix, panierdetails_qte, panierdetails_total')
                ->select('panier_id, panier_status, panier_montant, panier_montantmensuel, panier_nbarticle')
                ->select('article_id, article_nom')
                ->select('statuscommande_libelle')
                ->select('unitereference_nom')
                ->select('fournisseur_nom, fournisseur_contact, fournisseur_adresse')
                ->select('categorie_nom')
                ->from($this->_table)
                ->join('t_panier', 'panier_id = panierdetails_panier', 'inner')
                ->join('tr_article', 'article_id = panierdetails_article', 'inner')
                ->join('tr_unitereference', 'unitereference_id = article_unite', 'inner')
                ->join('tr_categorie', 'categorie_id = article_categorie', 'inner')
                ->join('tr_statuscommande', 'statuscommande_id = panier_status', 'LEFT')
                ->join('tr_fournisseur', 'fournisseur_id = article_fournisseur', 'INNER')
                ->order_by('panierdetails_id ASC');
        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }*/


    public function getPanierDetails()
    {
        $timetrackingDB = $this->load->database('timetracking', true);

        $this->db->select('panierdetails_id, panierdetails_panier, panierdetails_article, panierdetails_prix, panierdetails_qte, panierdetails_total')
                ->select('panier_id, panier_status, panier_montant, panier_montantmensuel, panier_nbarticle')
                ->select('article_id, article_nom')
                ->select('statuscommande_libelle')
                ->select('usr_nom, usr_prenom, usr_matricule, usr_site, usr_username')
                ->select('site_id, site_libelle')
                ->select('unitereference_nom')
                ->select('fournisseur_nom, fournisseur_contact, fournisseur_adresse')
                ->select('categorie_nom')
                ->from($this->_table)
                ->join('t_panier', 'panier_id = panierdetails_panier', 'inner')
                ->join('tr_article', 'article_id = panierdetails_article', 'inner')
                ->join('tr_unitereference', 'unitereference_id = article_unite', 'inner')
                ->join('tr_categorie', 'categorie_id = article_categorie', 'inner')
                ->join('tr_statuscommande', 'statuscommande_id = panier_status', 'LEFT')
                ->join('tr_fournisseur', 'fournisseur_id = article_fournisseur', 'INNER')
                ->join($timetrackingDB->database . '.tr_user', 'usr_id = panier_user', 'INNER')
                ->join($timetrackingDB->database . '.tr_site', 'usr_site = site_id', 'INNER')
                ->where('panier_status', 'VALID')
                ->order_by('panierdetails_id ASC');
        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

}

