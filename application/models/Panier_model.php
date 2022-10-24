<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Panier_model extends CI_Model {

    private $_id,
            $_user,
            $_montant,
            $_montantmensuel,
            $_status,
            $_datecrea,
            $_datemodif,
            $_table = 't_panier';
    
    //PUBLIC FUNCTIONS

	public function __construct() 
    {
        parent::__construct();
    }

    public function isUserAllowedToOrder($userId){
        $query = $this->db->select('panier_id')
                    ->where('panier_user', $userId)
                    ->where_in('panier_status', [PANIER_VALIDE])
                    ->get($this->_table);
        if($query->num_rows() > 0){
            return false;
        }
        return true;
    }

    public function getUserPanierActif($user){

        $this->db->select('panier_id, panier_user, panier_nbarticle, panier_montant, panier_montantmensuel, panier_status')
                    ->select('panier_status')
                    ->from($this->_table)
                    ->where('panier_status', PANIER_ENCOURS)
                    ->where('panier_user', $user);
        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        if($query->num_rows() > 0){
            return $query->row();
        }

        return false;
    }

    public function updatePanierById($id){
        $queryStr = "UPDATE t_panier
                        INNER JOIN (
                            SELECT panierdetails_panier, SUM(panierdetails_total) as total, SUM(panierdetails_qte) AS nbarticle
                            FROM t_panierdetails
                            GROUP BY panierdetails_panier
                        ) pnd ON panier_id = panierdetails_panier
                        SET 
                            panier_montant = pnd.total,
                            panier_montantmensuel = ROUND(pnd.total / 3, 2),
                            panier_nbarticle = pnd.nbarticle
                        WHERE panier_id = '$id'";
        $query = $this->db->query($queryStr);
        
        if($query){
            return true; 
        }else{
            return false;
        }
        
    }


    public function createPanier($user){
        $data = [
            'panier_user' => $user,
            'panier_montant' => '',
            'panier_montantmensuel' => '',
            'panier_status' => PANIER_ENCOURS,
            'panier_datecrea' => date('y-m-d H:i:s'),
            'panier_datemodif' => date('y-m-d H:i:s')
        ];
        $this->db->insert($this->_table, $data);
        $panierId = $this->db->insert_id();

        return $panierId;
    }

    public function getPanierById($id){
        $query = $this->db->select('panier_montant, panier_montantmensuel, panier_nbarticle, panier_status, panier_id')
                ->where('panier_id', $id)
                ->get($this->_table);

        if($query) return $query->row();
        return false;
    }

    public function cancelPanier($panierId, $userId){
        $this->db->set('panier_status', PANIER_ANNULE)
                    ->where('panier_id', $panierId)
                    ->where('panier_user', $userId)
                    ->update($this->_table);
        if($this->db->affected_rows() > 0) return true;
        return false;
    }

    public function validatePanier($panierId, $userId){
        $this->db->set('panier_status', PANIER_VALIDE)
                    ->where('panier_id', $panierId)
                    ->where('panier_user', $userId)
                    ->update($this->_table);
        if($this->db->affected_rows() > 0) return true;
        return false;
    }

    public function getListCommandeUser($userId){
        $query = $this->db->select('panier_id, panier_user, panier_montant, panier_nbarticle, panier_montantmensuel, panier_status')
                ->where('panier_user', $userId)
                ->get($this->_table);
        if($query->num_rows() > 0){
            return $query->result();
        }

        return false;
    }

    public function getListCommande($status = false){
        $this->db->select('panier_id, panier_user, panier_montant, panier_nbarticle, panier_montantmensuel, panier_status')
                    ->select('statuscommande_libelle')
                    ->join('tr_statuscommande', 'statuscommande_id = panier_status', 'LEFT');

        $query = $this->db->get($this->_table);
        if($query->num_rows() > 0){
            return $query->result();
        }

        return false;
    }

}

    