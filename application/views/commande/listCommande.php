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

    <div class="col-md-9">
        <div class="row mt-3">
        <div class="col-md-12">
            <div class="h4">Liste de mes commandes</div>
                
                <table id="details-panier" class="table-striped table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th class="number">Nb articles</th>
                            <th class="number">Montant</th>
                            <th class="number">A payer mensuel</th>
                            <th class="number">Etat de la commande</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($commandes as $commande) : ?>
                            <tr>
                                <td><?= $commande->panier_id ?></td>
                                <td class="number"><?= $commande->panier_nbarticle ?></td>                    
                                <td class="number"><?= number_format($commande->panier_montant, 2, ',', ' ') . ' Ar'?></td>
                                <td class="number " ><?= number_format($commande->panier_montantmensuel, 2, ',', ' ') . ' Ar'?></td>
                                <td class="number" ><?= $commande->panier_status ?></td>
                            </tr>
                        <?php endforeach; ?> 
                    </tbody>
                    
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
        
        
    })
</script>