
$(document).on('click',".update-ss-data",function(){
    var btn = $(this);
    console.log("ASDX");
    $.ajax({
        type: "POST",
        url: "ajax/update-ss-data.php",
        data: {
            id: btn.data("id"),
            shop_id : btn.data("shop"),
          },
        dataType: "json",
        success: function (zmienna) {
            console.log(zmienna)
            
            for (var key in zmienna){
                btn.parents("tr").prev().find(".ss-prod_name").text(zmienna[key]['prod_name'])
                btn.parents("tr").prev().find(".ss-prod_amount").text(zmienna[key]['prod_amount'])
                btn.parents("tr").prev().find(".ss-prod_price").text(zmienna[key]['prod_price'])
                btn.parents("tr").prev().find(".ss-prod_buy_price").text(zmienna[key]['prod_buy_price'])
            }
        },
      });
}) 


$(document).on('click',".add-single-product",function(e){
    e.preventDefault();
    console.log("?")
    let btn = $(this);
    let err = 0;
    $.ajax({
      type: "POST",
      url: "ajax/insert-single-product-to-group.php",
      data: {
        main_id: btn.data("id"),
        child_id: btn.parents("tr").find(".ss-child_id").val(),
        child_ilosc: btn.parents("tr").find(".ss-child_ilosc").val(),
        shop_id: btn.data("shop")
      },
      dataType: "json",
      success: function (zmienna) {
        let alert = "";
        console.log(zmienna);
        zmienna.forEach(function (x) {
          alert +=
            '<div class="alert alert-' +
            x["type"] +
            ' alert-dismissible fade" role="alert">';
          alert += "<strong>" + x["strong"] + "</strong> " + x["mess"];
          alert +=
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
          if (x["type"] === "danger") {
            err = 1;
          }
        });
        $(".alerts").append(alert);
        $(".alerts .alert").collapse();
      },
    });
})

$(document).on('click',".remove-single-product",function(e){
    console.log("?")
    let btn = $(this);
    let err = 0;
    $.ajax({
        type: "POST",
        url: "ajax/remove-this-product.php",
        data: {
            id: btn.data("id"),
          },
        dataType: "json",
        success: function (zmienna) {
            console.log(zmienna)
            zmienna.forEach(function(x){
                alert += '<div class="alert alert-'+x['type']+' alert-dismissible fade show" role="alert">';
                alert += '<strong>'+x['strong']+'</strong> ' + x['mess']
                alert += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
            
            if(x['type']==='danger'){
                err = 1;
            }


            })
            if( err !=1){
                console.log("WSZYSTKO GITEs");
                    btn.closest("tr").remove();
            }
            $(".alerts").append(alert)
        },
      });
})

console.log("WWW XD")
$(document).on('click',".send-new-quantity",function(e){
  console.log("aadsadad")
  var btn = $(this);
  var datastring = $(".add-zestaw").serialize();
  $.ajax({
    type: "POST",
    url: "ajax/update-quantity-ss.php",
    data: {
      id: btn.data("id"),
      shop: btn.data("shop")
    },
    dataType: "json",
    success: function (zmienna) {
      console.log("eeeeeeeeeeeeeeeeeeeeeee")
      console.log(zmienna)
      zmienna.forEach(function(x){
        alert += '<div class="alert alert-'+x['type']+' alert-dismissible fade show" role="alert">';
        alert += '<strong>'+x['strong']+'</strong> ' + x['mess']
        alert += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    
        $(".alerts").append(alert)


    })
    }
  });

})