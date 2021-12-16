$(".add-next-child").click(function(){
    let content = $(".together").html();
    let btn = $(this);
    // $('.alert').alert('close')
    btn.parent().before("<div class='border mb-3 p-3 together remove-me alert alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button>" + content + "</div>")
    console.log('aaa')
  })

  function reload_js(src) {
    $('script[src="' + src + '"]').remove();
    $('<script>').attr('src', src).appendTo('head');
}

$(document).ready(function(){
  document.title = "SkyAddon | "+$("#pageTitle").text();
})
$("#select-all").click(function(){
  
  if($(this).is(":checked")){
    $(".select-this-one").each(function(){
      $(this).prop('checked', true);
    })
  }else{
    $(".select-this-one").each(function () {
      $(this).prop("checked", false);
    });
  }
})

console.log("JESTEM!")
$(".change-subject").change(function(){
  console.log("zmienione")
  var val = $(this).find(':selected').attr('data-text');
  $(this).closest('form').find('textarea').text(val)

})

$(".clipboard").click(function () {
  var arr = [];
  $(".select-this-one:checked").each(function(){
    arr.push($(this).val());
  })
  console.log();
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val(arr.join("|")).select();
  document.execCommand("copy");
  $temp.remove();
});

$(".tooltip-toggle").click(function(){
  if($(this).hasClass("text-danger")){
    $(this).removeClass("text-danger");
    $(this).addClass("text-success");

    $(".tip").tooltip("show");
  }else{
 $(this).removeClass("text-success");
 $(this).addClass("text-danger");
 $(".tip").tooltip("hide");
  }
}) 

var len = $(".dataTable tr");
if(len.length>=1){

  var tableData  = $(".dataTable").DataTable({
    paging: false
  });

}


