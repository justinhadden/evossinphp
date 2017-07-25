$(document).ready(function(){

  $( "#datepicker" ).datepicker({
    inline: true,
    minDate: 3,
    maxDate: 14,
    dateFormat: "yy-mm-dd"
  });
  

  $( ".theform" ).dialog({
    open: function(){
      $(this).find("[type=submit]").hide();
    },
    title: "Comment",
    dialogClass: "no-close",
    autoOpen: false,
    buttons: [
      {
        text: "Submit",
        click: function(){
          $(".theform").submit();
        }     
      },
      {
        text: "Cancel",
        click: function() {
          $( this ).dialog( "close" );
        }
      }
    ]
  });

  $( "#clickme" ).click(function( event ) {
	  $( ".theform" ).dialog( "open" );
	  event.preventDefault();
  });

});