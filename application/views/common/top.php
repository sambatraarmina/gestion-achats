<!-- Page Content  -->
<div id="content" class="p-4 p-md-5">

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">

            <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse"
                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-bars"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="">
                    <i class="fa fa-user-circle-o mr-2"></i> Vous êtes : <strong><?= $username ?></strong>
                </div>
                
            </div>

            <div id="ctrl-buttons" class="col-md-8  offset-3 text-right" style="">

                <a href="<?=site_url('article/listArticle')?>" type="button" id="btn-monpanier" class="btn btn-sm btn-primary"><i class="fa fa-list pr-2"></i></i>Liste des articles  </a>

                <a href="<?=site_url('commande/panier')?>" type="button" id="btn-monpanier" class="btn btn-sm btn-info position-relative"><i class="fa fa-shopping-cart pr-2"></i>  Mon Panier <span id="top_montant" class="badge rounded-pill bg-light text-dark"><?= ($panier) ? number_format($panier->panier_montant, 2, ',', ' ') . 'Ar' : '0 Ar' ?> </span> <span id="top_nbarticle" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?= (($panier) ? $panier->panier_nbarticle : 0) ?><span class="visually-hidden">articles dans le panier</span></span></a>

                <a href="<?=site_url('commande/listCommande')?>" type="button" id="btn-monpanier" class="btn btn-sm btn-success"><i class="fa fa-list-alt pr-2"></i></i>Liste des commandes  </a>
                <?php if($isGestionAchat) : ?>
                <a href="<?=site_url('commande/gestionCommande')?>" type="button" id="btn-monpanier" class="btn btn-sm btn-danger"><i class="fa fa-file-text pr-2"></i></i>Gestion des commandes  </a>
                <?php endif; ?>
                <a href="<?= site_url('auth/doLogout') ?>" type="button" class="btn btn-sm btn-dark"><i class="fa fa-user pr-2"></i> Déconnexion  </a>

            </div>

        </div>


    </nav>

    <script type="text/javascript">
        
        $(document).ready(function(){

           
        })
    </script>

   
    
