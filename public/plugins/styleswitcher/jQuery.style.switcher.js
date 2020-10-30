// Theme color settings
$(document).ready(function(){
  var currentTheme = get();
  if(currentTheme)
  {
    $('#theme').attr({href: 'css/colors/'+currentTheme+'.css'});
  }
  // color selector
  $('#themecolors').on('click', 'a', function(){
    $('#themecolors li a').removeClass('working');
    $(this).addClass('working')
  });


  function store(name, valor) {
    
    /*
    if (typeof (Storage) !== "undefined") {
      localStorage.setItem(name, val);
    } else {
      window.alert('Please use a modern browser to properly view this template!');
    }
    */
   $.ajax({
        type:'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: "/atualizar/setup",
        data:{'valor': valor, 'coluna': 'theme_sistema'},
        success:function(data){
          console.log(data.response);
          window.location.reload();
        }
    });
  }
   $("*[data-theme]").click(function(e){
      e.preventDefault();
        var currentStyle = $(this).attr('data-theme');

        store('theme', currentStyle);
        $('#theme').attr({href: 'css/colors/'+currentStyle+'.css'})
    });

    

});
 function get() {
   
 }

$(document).ready(function(){
    $("*[data-theme]").click(function(e){
      e.preventDefault();
        var currentStyle = $(this).attr('data-theme');
 
        store('theme', currentStyle);
        $('#theme').attr({href: 'css/colors/'+currentStyle+'.css'});
    });

    var currentTheme = get();
    if(currentTheme)
    {
      $('#theme').attr({href: 'css/colors/'+currentTheme+'.css'});
      
    }
    // color selector
$('#themecolors').on('click', 'a', function(){
        $('#themecolors li a').removeClass('working');
        $(this).addClass('working')
      });
      
});
