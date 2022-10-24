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
<?php if($panier) : ?>
<div class="row">
    
    <div class="col-md-9">
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="h4">Récapitulatif de la commande</div>
                <div>
                    Veuillez confirmer et valider votre commande. Tous les détails de votre commande apparaissent ci-dessous.
                </div>
                <div class="my-2"><h5>Récapitulatif</h5></div>
                <table id="details-panier" class="table-striped table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Article</th>
                            <th class="number">Quantité</th>
                            <th class="number">Prix</th>
                            <th class="number">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($panierDetails as $article) : ?>
                            <tr>
                                <td><?= $article->article_nom ?></td>
                                <td class="number action-group"><?= $article->panierdetails_qte ?></td>                    
                                <td class="number prix_article"><?= number_format($article->panierdetails_prix, 2, ',', ' ') . ' Ar'?></td>
                                <td class="number total_article" ><?= number_format($article->panierdetails_total, 2, ',', ' ') . ' Ar'?></td>
                            </tr>
                        <?php endforeach; ?> 
                    </tbody>
                    
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="alert alert-info row mt-3 col-md-12 ">
            <h4 class="alert-heading">Infos du salarié</h4>
            <span>ZAFITIANA Sambatra Armina </span>
            <span>Site : Setex</span>
        </div> 

        <div class="alert alert-light row mt-3 col-md-12 ">
            <h4 class="alert-heading">Details de la commande</h4>
            <table>
                <tr>
                    <th>Numéro</th>
                    <td>#<?= $panier->panier_id ?></td>
                </tr>
                <tr>
                    <th>Total</th>
                    <td class="panier_montant"><?= number_format($panier->panier_montant, 2, ',', ' ') . ' Ar' ?></td>
                </tr>
                <tr>
                    <th>Mensuel</th>
                    <td class="panier_montantmensuel"><?= number_format($panier->panier_montantmensuel, 2, ',', ' ') . ' Ar' ?></td>
                </tr>
            </table>
            <a href="<?= site_url('commande/panier') ?>" class="btn btn-sm btn-info mt-3"><i class="fa fa-pencil-square"></i> Modifier mon panier</a>
            <button id="delete_cart" class="btn btn-sm btn-danger mt-3"><i class="fa fa-trash-o"></i> Annuler la commande</button>
            <button id="validate_cart" class="btn btn-sm btn-success mt-3"><i class="fa fa-check-square-o"></i> Valider la commande</button>
            <input type="hidden" id="panier_id" value="<?= $panier->panier_id ?>"/>
        </div> 
    </div>
</div>
<?php else : ?>
    <div class="alert alert-info text-center">Votre panier est vide</div>
<?php endif; ?>
<script type="text/javascript">
    $(document).ready(function(){
        
        $(document).on('click', '.action', function(){
            let toDo = $(this).data('action');
            let qteElement = $(this).closest('.action-group').find('.qte_article');
            let qte = parseInt(qteElement.val());
            if(toDo == 'remove' && qte > 0){
                qte -= 1;
            } else if(toDo == 'add'){
                qte += 1;
            }
            qteElement.val(qte);
            qteElement.trigger('change');
        })

        $(document).on('change', '.qte_article', function(){
            let panier = $(this).data('panier');
            let article = $(this).data('article');
            let qte = $(this).val();
            var ligneArticle = $(this).closest('tr');

            $.ajax({
                url : '<?= site_url('commande/updateCartArticle') ?>',
                dataType : 'json',
                data : {panier : panier, article : article, qte : qte},
                type : 'post',
                success : function(response){
                    if(!response.error){
                        let newQte = response.data.qte;
                        let newPrix = response.data.prixArticle;
                        let newTotal = response.data.totalArticle;
                        let totalPanier = response.data.panier.panier_montant;
                        let totalPanierMensuel = response.data.panier.panier_montantmensuel;
                        //Mise à jour de la ligne d'article
                        ligneArticle.find('.qte_article').val(newQte);
                        ligneArticle.find('.prix_article').html(newPrix);
                        ligneArticle.find('.total_article').html(newTotal);
                        //Mise à jour du total du tableau
                        $('.panier_montant').html(totalPanier.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' Ar');
                        $('.panier_montantmensuel').html(totalPanierMensuel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' Ar');
                    }
                }
            })
        })

        $(document).on('click', '.delete_article', function(){
            let panierdetails_id = $(this).data('ligne');
            let panier_id = $(this).data('panier');
            let ligne = $(this).closest('tr');
            if(confirm("Etes-vous sur de bien vouloir supprimer cette ligne d'article")){
                deleteLigneCart(panierdetails_id, panier_id, ligne);
            }
        })

        //Suppression du panier
        $('#delete_cart').on('click', function(){
            let panier = $('#panier_id').val();
            if(confirm("Etes-vous sur de bien vouloir supprimer ce panier")){

                $.ajax({
                    url : '<?= site_url("commande/deleteCart") ?>',
                    dataType : 'json',
                    data : {panier : panier},
                    type : 'post',
                    success : function(response){
                        window.location.href = '<?= site_url('article/listArticle') ?>';
                    }
                })
            }
        })

        //On valide le panier et transformer en commande
        $('#validate_cart').on('click', function(){
            let panier = $('#panier_id').val();

            $.ajax({
                url : '<?= site_url("commande/validateCart") ?>',
                dataType : 'json',
                data : {panier : panier},
                type : 'post',
                success : function(response){
                    window.location.href = '<?= site_url('article/listArticle') ?>';
                }
            })
            
        })

        function deleteLigneCart(panierdetails_id, panier_id, ligne){
            $.ajax({
                url : '<?= site_url('commande/deleteLigneArticle') ?>',
                dataType : 'json',
                data : { panier : panier_id, ligne : panierdetails_id },
                type : 'post',
                success : function(response){
                    if(!response.error && response.data){
                        let totalPanier = response.data.panier_montant;
                        let totalPanierMensuel = response.data.panier_montantmensuel;
                        //Supprimer la ligne
                        ligne.remove();
                        //Mettre à jour le montant total
                        $('.panier_montant').html(totalPanier.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' Ar');
                        $('.panier_montantmensuel').html(totalPanierMensuel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' Ar');
                    }
                }
            })
        }
    })
</script>