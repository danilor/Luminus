$( document ).ready(function(e){

    /**
     * Taken from: https://danpalmer.me/jquery-complexify/
     * @origin: https://danpalmer.me/jquery-complexify/
     */
    $("#password").complexify( { minimumChars:6 , banMode:"strict" },  function(valid, complexity) {
        console.log("Password complexity: " + complexity);
        $( ".bar" ).css( "width" , complexity + "%" );
        if( complexity > 0 ){
            $( ".bar" ).css( "background-color" , "red" );
        }
        if( complexity > 40 ){
            $( ".bar" ).css( "background-color" , "yellow" );
        }
        if( complexity > 80 ){
            $( ".bar" ).css( "background-color" , "green" );
        }
      });


});