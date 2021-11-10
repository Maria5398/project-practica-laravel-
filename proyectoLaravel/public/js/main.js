var url = 'http://127.0.0.1:8000'; //dominio
window.addEventListener("load", function () {
	//para cuando el cursos pase por encima de estas clases puedan ser seleccionadas
    $('.btn-like').css('cursor', 'pointer');

    $('.btn-dislike').css('cursor', 'pointer');

    function like() {
		//boton like
        $(document).on("click", ".btn-like", function (e) { 
			//seleciono el boton con su respectiva clase

            console.log('like');
			//al hacer click añado una clase al elemento se sseleccionado y remueve el antiguo
            $(this).addClass('btn-dislike').removeClass('btn-like');
			// TAMBIEN LE VA CAMBIAR EL ATRIBUTO QUE TIENE LA ETIQUETA POR OTRAS
			//EN ESTE CASO LA IMG, en vves que boton sea negro va ser rojo
            $(this).attr('src', url+'/img/heart-red.png');

			$.ajax({//peticiones ajax para que se guarden los cambios en la db
				url: url+'/like/'+$(this).data('id'),//ubicacion de img
				type: 'GET',//tipo de peticion
				success: function(response){//
					if(response.like){//comprobar si existe
						console.log('Has dado like a la publicacion');
					}else{
						console.log('Error al dar like');
					}
				}
			});

            dislike();//es para cuando le de click este se vuelva a capturar el evento

        });

    }

    like();

    function dislike() {

        $(document).on("click", ".btn-dislike", function (e) {

            console.log('dislike');

            $(this).addClass('btn-like').removeClass('btn-dislike');

            $(this).attr('src', url+'/img/heart-black.png');

			$.ajax({
				url: url+'/dislike/'+$(this).data('id'),
				type: 'GET',
				success: function(response){
					if(response.like){
						console.log('Has dado dislike a la publicacion');
					}else{
						console.log('Error al dar dislike');
					}
				}
			});

            like();

        });

    }

    dislike();
	
	//buscador
	$('#buscador').submit(function(e){
		$(this).attr('action',url+'/gente/'+$('#buscador #search').val());
	});



});

