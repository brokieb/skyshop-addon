console.log("!!!!!!!!!!!!!!!!!!!!!!!!!!!!q")
$(".add-zestaw").submit(function (e) {
  e.preventDefault();
  // alert("TEST")
console.log("asd");
  var datastring = $(".add-zestaw").serialize();
  $.ajax({
    type: "POST",
    url: "ajax/insert-group.php",
    data: datastring,
    dataType: "json",
    success: function (zmienna) {
      let alert = "";
      let err = 0;
      console.log("AAA");
      console.log(zmienna,'WWWWWWWWWWW');
      zmienna.forEach(function (x) {
        alert +=
          '<div class="alert alert-' +
          x["type"] +
          ' alert-dismissible fade " role="alert">';
        alert += "<strong>" + x["strong"] + "</strong> " + x["mess"];
        alert +=
          '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';

        if (x["type"] === "danger") {
          err = 1;
        }
      });
      if (err != 1) {
        console.log("WSZYSTKO GITEs");
        $(".clean-me").each(function () {
          $(this).val("");
        });
        $(".remove-me").each(function () {
          $(this).remove();
        });
      } 
      console.log(zmienna);

      $(".alerts").append(alert);
      $(".alerts .alert").collapse();
    }
  });
});

$(".remove-this-product").click(function () {
  console.log("?");
  let btn = $(this);
  let err = 0;
  var alert = "";
  $.ajax({
    type: "POST",
    url: "ajax/remove-this-product.php",
    data: {
      id: btn.data("id"),
    },
    dataType: "json",
    success: function (zmienna) {
      console.log(zmienna,"AAAA");
      zmienna.forEach(function (x) {
        alert +='<div class="alert alert-' + x["type"] +' alert-dismissible fade" role="alert">';
        alert += "<strong>" + x["strong"] + "</strong> " + x["mess"];
        alert +='<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';

        if (x["type"] === "danger") {
          err = 1;
        }
      });
      if (err != 1) {
        console.log("WSZYSTKO GITEs");
        btn.parents("tr").remove();
      }
      $(".alerts").append(alert);
      $(".alerts .alert").collapse();
    },
  });
});

$(".remove-shop").click(function () {
  let btn = $(this);
  let err = 0;
var alert = "";
let confirmAction = confirm("Usunięcie sklepu będzie skutkowało usunięciem wszystkich zapisanych zestawów na tej stronie (nie zostaną usunięte z właściwego sklepu) Czy chcesz kontynuować?");
if (confirmAction) {
  $.ajax({
    type: "POST",
    url: "ajax/remove-shop.php",
    data: {
      id: btn.data("id"),
    },
    dataType: "json",
    success: function (zmienna) {
      console.log(zmienna);
      zmienna.forEach(function (x) {
          console.log("AAAA!!");
        alert += '<div class="alert alert-' + x["type"] +' alert-dismissible fade" role="alert">';
        alert += "<strong>" + x["strong"] + "</strong> " + x["mess"];
        alert +='<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';

        if (x["type"] === "danger") {
          err = 1;
        }
      });
    //   if (err != 1) {
    //     console.log("WSZYSTKO GITEs");
    //     btn.parents("tr").remove();
    //   }
      $(".alerts").append(alert);
      $(".alerts .alert").collapse();
    }
  });
}else{
    console.log("anulowano")
}
});


$(".close-alert").click(function () {
var btn = $(this);
$.ajax({
  type: "POST",
  url: "ajax/remove-this-alert.php",
  data: {
    id: btn.data("id"),
  },
  success:function(){
    btn.closest("div").remove()
  }
});

});

$(".this-group-details").click(function () {
  var tr = $(this).closest("tr");
  var btn = $(this);
  var row = tableData.row(tr);
  var bg = $(this).closest("tr").attr("class");
  $.ajax({
    type: "POST",
    url: "ajax/this-group-details.php",
    data: {
      id: btn.data("id"),
      shop_id: btn.data("shop")
    },
    dataType: "html",
    success: function (zmienna) {
      // console.log(zmienna)
      if (row.child.isShown()) {
        // This row is already open - close it
        btn.text("ROZWIŃ");
        row.child.hide();
        tr.css("border", "none");
        tr.removeClass("shown");
      } else {
        // Open this row
        btn.text("ZWIŃ");
        row.child(zmienna).show();
        tr.css({ border: "5px solid black", "border-bottom": "0" });
        row
          .child()
          .attr("class", bg + " additional")
          .css({ border: "5px solid black", "border-top": "0" });
      }
    },
  });
});

var exampleModal = document.getElementById('myModal')
exampleModal.addEventListener('show.bs.modal', function(event) {
    // Button that triggered the modal
    var button = event.relatedTarget
        // Extract info from data-bs-* attributes
    var site = button.getAttribute('data-site')
    var title = button.getAttribute('data-title')
    console.log("MODAL!!");
    // If necessary, you could initiate an AJAX request here
    // and then do the updating in a callback.
    $.ajax({
        type: "POST",
        url: "ajax/" + site + ".php",
        data: {
            id: button.getAttribute('data-id'),
            sklep: button.getAttribute('data-sklep')
        },
        success: function(zawartosc) {
            console.log();
            var modalTitle = exampleModal.querySelector('.modal-title')
            var modalBody = exampleModal.querySelector('.modal-body')

            modalTitle.textContent = title

            modalBody.innerHTML = zawartosc
            $(modalBody).find(".checks").each(function() {
                if ($(this).find("*").length == 0) {
                    $(this).closest(".checks-main").remove();
                }
            })
        }
    });
    // Update the modal's content.
    console.log(title);

})


$(".save-template").click(function(){
var button = $(this);
  $.ajax({
    type: "POST",
    url: "ajax/save-template.php",
    data: {
        id: button.closest('form').find('select').val(),
        text: button.closest('form').find('textarea').val()
    },
    success: function(zawartosc) {
        button.closest('form').find('textarea').val("zmienione")
        alert = "";
        alert += '<div class="alert alert-success alert-dismissible fade" role="alert">';
        alert += "<strong>Sukces</strong> Template został pomyślnie zaktualizowany" ;
        alert +=' <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        button.closest('form').find('textarea').val("0")
    //   if (err != 1) {
    //     console.log("WSZYSTKO GITEs");
    //     btn.parents("tr").remove();
    //   }
      $(".alerts").append(alert);
      $(".alerts .alert").collapse();

    }
});
})