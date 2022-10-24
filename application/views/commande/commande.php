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
<?php if($panier !== FALSE) : ?>
<div class="row">

    <div class="col-md-9">
        <div class="row mt-3">
            
                <div class="col-md-12">
                    <div class="h4">Commande N° #<span><?= $panier->panier_id ?></span> <span class="nb_articles small">(<?= $panier->panier_nbarticle ?> articles)</span></div>
                    <table id="details-panier" class="table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th class="number">Quantité</th>
                                <th>Article</th>
                                <th>Catégorie</th>
                                <th class="number">Prix</th>
                                <th class="number">Sous-total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($panierDetails as $article) : ?>
                                <tr>
                                    <td class="number action-group"><button class="btn btn-sm btn-secondary action" data-action="remove"><i class="fa fa-minus-circle"></i></button> <input type="text" class="qte_article" size="1" data-panier="<?= $article->panier_id ?>" data-article="<?= $article->panierdetails_article ?>" value="<?= $article->panierdetails_qte ?>"> <button class="btn btn-sm btn-secondary action" data-action="add"><i class="fa fa-plus-circle"></i></button></td>
                                    <td><?= $article->article_nom ?></td>
                                    <td><?= $article->categorie_nom ?></td>
                                    <td class="number prix_article"><?= number_format($article->panierdetails_prix, 2, ',', ' ') . ' Ar'?></td>
                                    <td class="number total_article" ><?= number_format($article->panierdetails_total, 2, ',', ' ') . ' Ar'?></td>
                                    <td><button class="btn btn-sm btn-danger delete_article" data-panier="<?= $article->panier_id ?>" data-ligne="<?= $article->panierdetails_id ?>"><i class="fa fa-trash"></i></button></td>
                                </tr>
                            <?php endforeach; ?> 
                        </tbody>
                        <tfoot style="display: none">
                            <tr>

                                <th colspan="4" style="text-align:right">Total de votre commande</th>
                                <th class="number panier_montant" style="text-align:center"><?= number_format($panier->panier_montant, 2, ',', ' ') . ' Ar' ?></th>
                                <th></th>
                            </tr>
                            <tr>

                                <th colspan="4" style="text-align:right">Ce que vous allez payer par mois pendant 3 mois</th>
                                <th class="number panier_montantmensuel" style="text-align:center"><?= number_format($panier->panier_montantmensuel, 2, ',', ' ') . ' Ar' ?></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="alert alert-info row mt-3 col-md-12 ">
            <h4 class="alert-heading">Infos du salarié</h4>
            <span><?= $userInfos['nom'] . ' ' . $userInfos['prenom'] ?> </span>
            <span>Site : <?= $userInfos['site'] ?></span>
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
                    <th>Mensuel à payer</th>
                    <td class="panier_montantmensuel"><?= number_format($panier->panier_montantmensuel, 2, ',', ' ') . ' Ar' ?></td>
                </tr>
            </table>
            <a href="<?= site_url('article/listArticle') ?>" class="btn btn-sm btn-warning mt-3"><i class="fa fa-shopping-basket"></i> Continuer mes achats</a>
            <a href="<?= site_url('commande/recapitulatif') ?>" class="btn btn-sm btn-primary mt-3"><i class="fa fa-check-square-o"></i> Poursuivre</a>

        </div> 
    </div>
</div>
<?php else: ?>
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
                        let nbarticle = response.data.panier.panier_nbarticle;
                        //Mise à jour de la ligne d'article
                        ligneArticle.find('.qte_article').val(newQte);
                        ligneArticle.find('.prix_article').html(newPrix);
                        ligneArticle.find('.total_article').html(newTotal);
                        //Mise à jour du total du tableau
                        $('.panier_montant').html(totalPanier.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' Ar');
                        $('.panier_montantmensuel').html(totalPanierMensuel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' Ar');
                        $('#top_nbarticle').html(nbarticle);
                        $('#top_montant').html(totalPanier.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' Ar');
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

        //On valide le panier et transformer en commande
        $('#validate_cart').on('click', function(){

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