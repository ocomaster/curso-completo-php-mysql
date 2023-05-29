<!-- Archivo principal que contiene las funciones del sistema -->
<?php 
    #Conexion a la base de datos
    function conexion(){
         $pdo = new PDO('mysql:host=localhost;dbname=inventario','root','');
         return $pdo;
    }
   
    //Prueba de conexion
    //echo "Conexion exitosa";

    //Prueba inserccion  datos
     //$pdo->query("INSERT INTO categoria(categoria_nombre, categoria_ubicacion) values('Accesorios','Piso 2') ");


     #Funcion para verificacion de datos#
     
     function verificar_datos($filtro,$cadena){
        if (preg_match("/^".$filtro."$/",$cadena)) {
            return false;
        } else {
            return true;
        }       
     }

    //  $nombre = "Carlos5";
    //  if (verificar_datos("[a-zA-Z]{6,10}",$nombre)) {
    //      echo "los datos no coinciden";
    //  }    
    
    #Funcion evitatar inyeccion SQL desde formularios // Limpiar cadenas de texto
    function limpiar_cadena($cadena){
		$cadena=trim($cadena);
		$cadena=stripslashes($cadena);//Eliminacion de barras invertidas
		$cadena=str_ireplace("<script>", "", $cadena);
		$cadena=str_ireplace("</script>", "", $cadena);
		$cadena=str_ireplace("<script src", "", $cadena);
		$cadena=str_ireplace("<script type=", "", $cadena);
		$cadena=str_ireplace("SELECT * FROM", "", $cadena);
		$cadena=str_ireplace("DELETE FROM", "", $cadena);
		$cadena=str_ireplace("INSERT INTO", "", $cadena);
		$cadena=str_ireplace("DROP TABLE", "", $cadena);
		$cadena=str_ireplace("DROP DATABASE", "", $cadena);
		$cadena=str_ireplace("TRUNCATE TABLE", "", $cadena);
		$cadena=str_ireplace("SHOW TABLES;", "", $cadena);
		$cadena=str_ireplace("SHOW DATABASES;", "", $cadena);
		$cadena=str_ireplace("<?php", "", $cadena);
		$cadena=str_ireplace("?>", "", $cadena);
		$cadena=str_ireplace("--", "", $cadena);
		$cadena=str_ireplace("^", "", $cadena);
		$cadena=str_ireplace("<", "", $cadena);
		$cadena=str_ireplace("[", "", $cadena);
		$cadena=str_ireplace("]", "", $cadena);
		$cadena=str_ireplace("==", "", $cadena);
		$cadena=str_ireplace(";", "", $cadena);
		$cadena=str_ireplace("::", "", $cadena);
		$cadena=trim($cadena);
		$cadena=stripslashes($cadena);
		return $cadena;
	}

    // $texo = " Hola mundo <script> ::";
    // echo limpiar_cadena($texo);// devuelve "Hola Mundo"

    #Funcion renombrar fotos
    function renombrar_fotos($nombre){
		$nombre=str_ireplace(" ", "_", $nombre);//funcion retira espacio en blanco remplazandolo por un guion bajo
		$nombre=str_ireplace("/", "_", $nombre);
		$nombre=str_ireplace("#", "_", $nombre);
		$nombre=str_ireplace("-", "_", $nombre);
		$nombre=str_ireplace("$", "_", $nombre);
		$nombre=str_ireplace(".", "_", $nombre);
		$nombre=str_ireplace(",", "_", $nombre);
		$nombre=$nombre."_".rand(0,100);
		return $nombre;
	}
    // $foto = "Play station 5/ .s s" ;
    // echo renombrar_fotos($foto); //Resultado Play_station_5____ss_63

    #Generar funcion de paginador de tablas #
    function paginador_tablas($pagina, $Npaginas,$url,$botones){
        $tabla='<nav class="pagination is-centered is-rounded" role="navigation" aria-label="pagination">';

        if($pagina<=1){
			$tabla.='
			<a class="pagination-previous is-disabled" disabled >Anterior</a>
			<ul class="pagination-list">';
		}else{
			$tabla.='
			<a class="pagination-previous" href="'.$url.($pagina-1).'" >Anterior</a>
			<ul class="pagination-list">
				<li><a class="pagination-link" href="'.$url.'1">1</a></li>
				<li><span class="pagination-ellipsis">&hellip;</span></li>
			';
		}

		$ci=0;
		for($i=$pagina; $i<=$Npaginas; $i++){
			if($ci>=$botones){
				break;
			}
			if($pagina==$i){
				$tabla.='<li><a class="pagination-link is-current" href="'.$url.$i.'">'.$i.'</a></li>';
			}else{
				$tabla.='<li><a class="pagination-link" href="'.$url.$i.'">'.$i.'</a></li>';
			}
			$ci++;
		}

		if($pagina==$Npaginas){
			$tabla.='
			</ul>
			<a class="pagination-next is-disabled" disabled >Siguiente</a>
			';
		}else{
			$tabla.='
				<li><span class="pagination-ellipsis">&hellip;</span></li>
				<li><a class="pagination-link" href="'.$url.$Npaginas.'">'.$Npaginas.'</a></li>
			</ul>
			<a class="pagination-next" href="'.$url.($pagina+1).'" >Siguiente</a>
			';
		}

		$tabla.='</nav>';
		return $tabla;
	}

    




?>