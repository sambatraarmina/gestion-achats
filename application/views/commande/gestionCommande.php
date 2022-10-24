<style>
    table .number {
        text-align: center;
    }
    table tfoot:before {
        line-height:1.5em;
        content:".";
        color:white;
        display:block;
    }
</style>
<?php if($commandes !== FALSE) : ?>
<div class="row">

    <div class="col-md-12">
        <div class="row mt-3">
        <div class="col-md-12">
            <div class="h4">Liste des commandes</div>
                
                <table id="list-commande" class="table-striped table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th class="number">Commande N°</th>
                            <th>Site</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Username</th>
                           
                            <th class="number">Article</th>
                            <th class="number">Prix</th>
                            <th class="number">Qte</th>
                            <th class="number">Unité ref</th>
                            <th class="number">Total Article</th>
                            <th class="number">Catégorie</th>
                            <th class="number">Nb articles</th>
                            <th class="number">Total Achat</th> 
                            <th class="number">A payer mensuel</th>
                            <th class="number">Fournisseur</th>
                            <th class="number notexport">Etat de la commande</th>
                        </tr>
                    </thead>
                    
                </table>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
    <div class="alert alert-info text-center">Vous n'avez pas de commande à visualiser</div>
<?php endif; ?>

<script type="text/javascript">
    $(document).ready(function(){
        
        var myTable = $("#list-commande").DataTable({

             dom: 'Blfrtip',
            buttons: [
                {
                    extend : 'excelHtml5',
                    exportOptions : {
                        orthogonal : 'export'
                    }
                },
                
            ],
            language : {
                url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
            },
            ajax : "<?= site_url("commande/getListCommande"); ?>",
            order: [[0, 'asc']],
            columns : [
                { data : "panier_id" },
                { data : "site_libelle" },
                { data : "usr_nom" },
                { data : "usr_prenom" },
                { data : "usr_username" },
                
                { data : "article_nom" },
                {
                    data : "panierdetails_prix",
                    render : $.fn.dataTable.render.number( ' ', ',', 2, '', ' Ar' )
                },
                { data : "panierdetails_qte" },
                { data : "unitereference_nom" },
                {
                    data : "panierdetails_total",
                    render : $.fn.dataTable.render.number( ' ', ',', 2, '', ' Ar' )
                },
                { data : "categorie_nom" },
                { data : "panier_nbarticle" },
                { 
                    data : "panier_montant",
                    render: $.fn.dataTable.render.number( ' ', ',', 2, '', ' Ar' )
                },
                { 
                    data : "panier_montantmensuel",
                    render: $.fn.dataTable.render.number( ' ', ',', 2, '', ' Ar' )
                },
                { data : "fournisseur_nom" },
                { data : "statuscommande_libelle" }
            ]
        });
    })
</script>