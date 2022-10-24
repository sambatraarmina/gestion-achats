<div class="row container-fluid">
    <div class="row">
        <div class="col-md-12  title-page">
            <h2>Liste des articles</h2>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <table id="list-article" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Catégorie</th>
                        <th>Article</th>
                        <th>Prix</th>
                        <th>Unité</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        //initialisation datatable
        var myTable = $("#list-article").DataTable({
            language : {
                url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
            },

            ajax : "<?= site_url("article/getListArticle"); ?>",
            order: [[1, 'asc']],
            columns : [
                { data : "categorie_nom" },
                { data : "article_nom" },
                { 
                    data : "article_prix",
                    render: $.fn.dataTable.render.number( ' ', ',', 2, '', ' Ar' )
                },
                { data : "unitereference" },
                {
                    data : "total",
                    render : $.fn.dataTable.render.number( ' ', ',', 2, '', ' Ar' )
                },
                { 
                    data : null,
                    width : "15%",
                    render : function(data, type, row){
                        //console.log(data)
                        return '<div class="bloc-action" data-article="'+data.article_id+'"><button type="button" class="remove-from-cart btn btn-sm btn-secondary mr-1"><i class="fa fa-minus-circle"></i></button>'+
                                '<input type="text" class="qty mr-1" style="width:40px" value="'+((data.qte) ? data.qte : '')+'"/>'+
                                '<button type="button" class="add-to-cart btn btn-sm btn-secondary mr-1"><i class="fa fa-plus-circle"></i></button></div>'
                    } 
                }
            ]
        });

        /**
         * Event sur le bouton add-to-cart 
         * 
         */
        $(document).on('click', '.add-to-cart', function(){
            let parent = $(this).closest('.bloc-action');
            let qtyNode = parent.find('.qty');
            let article = parent.data('article');
            
            let currQty = parseInt(qtyNode.val());
            if(!currQty) currQty = 0;
            currQty += 1;
            let qty = currQty;

            qtyNode.val(qty);
            updateCart(qty, article);
        })

        /**
         * Event sur le bouton remove-from-cart 
         */
        $(document).on('click', '.remove-from-cart', function(){
            let parent = $(this).closest('.bloc-action');
            let qtyNode = parent.find('.qty');
            let article = parent.data('article');
            
            let currQty = parseInt(qtyNode.val());
            if(currQty > 0){
                if(!currQty) currQty = 0;
                currQty -= 1;
                let qty = currQty;
                qtyNode.val(qty);
                updateCart(qty, article);
            }
        })

        /**
         * Fonction de mise à jour panier
         */
        function updateCart(qty, article){

            $.ajax({
                url : '<?= site_url("article/updateUserCart") ?>',
                dataType : 'json',
                data : {qty : qty, article : article},
                type : 'POST',
                success : function(response){
                    if(!response.error){
                        myTable.ajax.reload();
                        let montant = response.data.panier_montant;
                        let nbarticle = response.data.panier_nbarticle;
                        $('#top_nbarticle').html(nbarticle);
                        $('#top_montant').html(montant.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' Ar');
                    }
                }
            })
        }
    })
    
</script>