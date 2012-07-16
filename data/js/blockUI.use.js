    $(document).ready(function() { 

        $('#basketbtn').click(function() { 
            // update the block message 
            $.blockUI({ timeout:   4000,
						message: "<h2>Добавляю в корзину...</h2>" }); 
 
            $.ajax({ 
                url: site_url + '/shopcart/add/' + page_id, 
                cache: false, 
                complete: function() { 
                    // unblock when remote call returns 
                    $.unblockUI(); 
                } 
            }); 
        }); 
    }); 