$('.searchbar').on('keyup', function(){
	$.ajax({
        type : 'GET', // envoi des données en GET ou POST
        url : '/spotify_explorer/web/search-results' , // url du fichier de traitement
        data : 'q='+$('.searchbar').val() , // données à envoyer en  GET ou POST
        dataType: "json",
        success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
        	$('.results').empty();
          if(data === '')
            $('.searchresults').append('Pas de résultats');
          for(var i in data.artists.items) {
            $('.results').append("<a href='/spotify_explorer/web/artist/"+ encodeURIComponent(data.artists.items[i].id) +"'>"+data.artists.items[i].name+"</a>");
          }
        }
      });
});