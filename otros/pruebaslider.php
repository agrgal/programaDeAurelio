  <!-- ***************************** -->
  <!-- Linkar a hojas de estilo CSS -->
  <!-- ***************************** -->
  <?php include_once("./css/cargarestiloscss.php"); ?>
  
  <!---
  <script src="./jquery/external/jquery/jquery.js"></script>
  <script src="./jquery/jquerymousewheel/jquery.mousewheel.js"></script>   
  <script src="./jquery/jquery-ui.js"></script>
  <script src="./jquery/jquery-ui.min.js"></script> -->
  
  
  <!-- Esta es la combinación de scripts que funciona para el mousewheel -->
  <!-- Conserva el jquery que tenía pero los otros dos los obtiene desde internet -->
  <script src="./jquery/external/jquery/jquery.js"></script>
  <!-- <script src="//code.jquery.com/jquery-1.10.2.js"></script> -->  
  <script src="https://raw.githubusercontent.com/brandonaaron/jquery-mousewheel/master/jquery.mousewheel.js"></script>
  <!-- <script src="./jquery/jquerymousewheel/jquery.mousewheel.js"></script> -->
  <script src="//code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>


     <h3>Jquery ui slider with mousewheel support</h3> 
 <div align="center">    
 <div id="slider" style="width:200px;"></div>
     </div>
     
<br>
    Slider current value : <span id="slider_val"></span>    

<script>
	$(function() {
		
	alert($().jquery);
	alert($.ui.version);
		
    var value;
    $( "#slider" ).slider({
        slide: function( event, ui ) {
            value = $( "#slider" ).slider( "value" );
            $('#slider_val').html(value);
        }
    });
    
    value = $( "#slider" ).slider( "value" );
    $('#slider_val').html(value);
    
    $('#slider').on('mousewheel', function(event) {
        event.preventDefault();
        value = $( "#slider" ).slider( "value" );
        
        //console.log(event.deltaX, event.deltaY, event.deltaFactor);
        
        //Mousewheel Scrolled up
        if(event.deltaY == -1){
            //alert("scrolled down");
            value = value+1;
            $( "#slider" ).slider("value", value);
            $('#slider_val').html(value);
        }
        //Mousewheel Scrolled down
        else if(event.deltaY == 1){
            //alert("scrolled up");
            value = value-1;
            $( "#slider" ).slider( "value", value );
            $('#slider_val').html(value);
        }
    });
    
});     

</script>
