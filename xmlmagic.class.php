<?php
//Clase PHP para trabajar con archivos XML. CRUD...
/*
	Copyright (c) 2017 Arturo Vásquez Soluciones de Sistemas 2716
	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
class xmlmagic{
	private $xmldir,$archivofis;
	private $valores=array();
	private $clases=array();
	private $class_ppal=array();
	private $nodos=array();
	private $perte=array();
	private $estrucxml_arr=array();
	private $archivoconf=array();
	private $menu_padre=array();
	private $submenu_padre=array();
	private $linkmenu_padre=array();
	private $pertenece_padre=array();
	private $niveles=array();
	private $cant_clases=0;
	private $cant_nodos=0;
	private $estrucxml,$anode,$encodetext,$nmclass,$mdcstr,$encodestat;
	private $primeravez=0;
	private $etiqcier;
	private $index_menu=0;
	private $index_submenu=0;
	private $index_linksubmenu=0;
	private $index_niveles=0;
	private $lastmenu;
	private $menudir,$menuhtmldir;
	private $namefilemenu;
	private $childs;
	private $cantmenuppal;
	private $i=0;
	private $u=0;
	//variables de los menus///////////////////////////////////////////////////
	private $lcabecera,$hcabecera,$lmenu,$hmenu,$nmenu,$forecabecera,$foremenu;
	///////////////////////////////////////////////////////////////////////////
	///Condition 1 – Presence of a static member variable
	private static $_instance = null;
	
	///Condition 2 – Locked down the constructor
	private function __construct() { } //Prevent any oustide instantiation of this class
	
	///Condition 3 – Prevent any object or instance of that class to be cloned
	private function __clone() { } //Prevent any copy of this object
	
	///Condition 4 – Have a single globally accessible static method
	public static function getInstance(){
		if( !is_object(self::$_instance) ) //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
		self::$_instance = new xmlmagic();
		return self::$_instance;
	}
	//IMPRIMIR EN UTF8
	function echoUtf8($cadena){
		$cadena=utf8_decode($cadena);
		echo($cadena);
		return 0;
	}
	function decoUtf8($cadena){
		$cadena=utf8_decode($cadena);
		echo($cadena);
		return 0;
	}
	function encoUtf8($cadena){
		$cadena=utf8_encode($cadena);
		echo($cadena);
		return 0;
	}
	//ESTABLECE EL ARCHIVO XML A CREAR POSTERIORMENTE
	function XMLDocNew($dircomplet){
		$this->xmldir=$dircomplet;
		return 0;
	}
	//CREA una clase O ESTRUCTURA XML (SIN ESCRIBIR AL ARCHIVO TODAVIA)
	function MkClass($class){
		$nodepal=$this->getMainNode();
		$cant_clases=$this->cant_clases;

		if($this->xmldir!=''){
			$this->clases[$nodepal][$cant_clases]=$class;
			$this->cant_clases++;
			return 0;
		}
		else{
			return -1;
		}
	}
	//ESTABLECE UNA CLASE XML COMO LA PREDETERMINADA A TRABAJAR EN ELLA
	function SetClass($class){
		$this->nmclass=$class;
		return 0;
	}
	function setClase($class){
		$nodepal=$this->getMainNode();
		$cant_clases=$this->cant_clases;

		if($this->xmldir!=''){
			$this->clases[$nodepal][$cant_clases]=$class;
			$this->cant_clases++;
			$this->nmclass=$class;
			return 0;
		}
		else{
			return -1;
		}
	}

	//CREA el NODO PRINCIPAL de todas las clases
	function setMainNode($nombre){
		$this->nodeppal=$nombre;
		return 0;
	}
	//CREA UN SUB-NODO
	function setSubNode($nombre,$valor,$option){
		$clase=$this->nmclass;
		if($this->nodos[$clase][$nombre]==''){
			$this->nodos[$clase][$nombre]=$valor;
			$this->perte[$nombre]=$clase;
			$this->anode=$nombre;
			$this->cant_nodos++;
		}
		else{
			return -1;
		}

		if($option=='u'){
			$this->getOutput($option);
		}
		if($option=='r'){
			$this->getOutput($option);
		}
		elseif($option=='uf'){
			$this->getOutput($option);
		}
		return 0;
	}

	//ESTABLECE EL VALOR DE UN NODO X
	function setValSubNode($nombre,$valor){
		$clase=$this->nmclass;
		$this->nodos[$clase][$nombre]=$valor;
		$this->anode=$nombre;
		return 0;
	}
	//OBTIENE EL VALOR DEL ULTIMO NODO CREADO
	function getValNode(){
		$clase=$this->nmclass;
		$node=$this->anode;
		$valor=$this->nodos[$clase][$node];

		if($valor!=''){
			return $valor;
		}
		else{
			return -1;
		}
	}
	//OBTIENE EL VALOR DEL ULTIMO CLASE CREADO
	function getvalClass(){
		$nodepal=$this->getMainNode();
		$cant_clases=$this->cant_clases;
		$valor=$this->clases[$nodepal][$cant_clases];

		if($valor!=''){
			return $valor;
		}
		else{
			return -1;
		}
	}
	//OBTIENE UN VALOR DEL NODO QUE SE LE INDIQUE
	function getValNode_dif($node){
		$clase=$this->nmclass;
		$valor=$this->nodos[$clase][$node];

		if($valor!=''){
			return $valor;
		}
		else{
			return -1;
		}
	}
	//OBTIENE EL NODO PRINCIPAL
	function getMainNode(){
		return $this->nodeppal;
	}
	//OBTIENE LA CLASE CON LA QUE SE ESTA TRABAJANDO ACTUALMENTE
	function getActualClass(){
		return $this->nmclass;
	}
	//OBTIENE EL ARCHIVO ACTUAL
	function getActualFile(){
		return $this->xmldir;
	}
	//OBTIENE EL ULTIMO NODO QUE SE CREO
	function getActualNode(){
		return $this->anode;
	}
	//OBTIENE EL NUMERO DE CLASES CREADAS
	function getAllNumClases(){
		$clase=$this->nmclass;
		$u=$this->cant_clases;
		return $u;
	}
	//OBTIENE EL NUMERO DE NODOS CREADOS
	function getAllNumNodes(){
		$clase=$this->nmclass;
		$u=$this->cant_nodos;
		return $u;
	}
	//DEVUELVE UNA LISTA DE TODAS LAS CLASES CREADAS
	function ListClases(){
		$nodepal=$this->getMainNode();
		echo("Lista de Clases: <br>");
		foreach ($this->clases[$nodepal] as $key=>$value) {
		    echo("$value<br/>\n");
		}
	}
	//DEVUELVE UNA LISTA DE TODOS LOS NODOS CREADOS
	function ListNodos(){
		$clase=$this->nmclass;
		$nodosnum=count($this->nodos[$clase]);
		echo("Lista de Nodos: <br>");
		foreach ($this->nodos[$clase] as $key=>$value) {
		    echo("Clave: $key; Valor: $value<br />\n");
		}
	}
	//DEVUELVE UN STRING CON LA ESTRUCTURA QUE SE HA CREADO
	function strGetStructClass(){
		//Armar la escrutctura del XML
		$claseppal=$this->getMainNode();
		//imprimir la clase ppal
		$cant_clases=$this->cant_clases;

		$enctype=$this->encodetext;
		if($enctype!=''){
			$this->estrucxml="<?xml version='1.0' encoding='".$enctype."'?>\n";
		}
		else{
			$this->estrucxml="<?xml version='1.0' encoding='utf-8'?>\n";
		}

		$this->estrucxml.="<".$claseppal.">\n";
		foreach ($this->clases[$claseppal] as $llave=>$valor) {
			$this->estrucxml.="\t<".$valor.">\n";
				foreach ($this->nodos[$valor] as $key=>$value) {
					if($this->perte[$key]==$valor){
					    $this->estrucxml.="\t\t<".$key.">".$value."</".$key.">\n";
					}
				}
			$this->estrucxml.="\t</".$valor.">\n";
		}
		$this->estrucxml.="</".$claseppal.">\n";
		//$directorio
		if($this->estrucxml!=''){
			return $this->estrucxml;
		}
		else{
			return -1;
		}
	}
	//Establece la codificacion del archivo XML
	function setEncoding($encode){
		$this->encodetext=$encode;
	}

	function getEncoding(){
		if($this->encodetext!=''){
			return $this->encodetext;
		}
		else{
			return -1;
		}
	}
	//Establece la codificacion del head XML
	function setCodeStat($encode){
		$this->encodestat=$encode;
	}

	function getCodeStat(){
		if($this->encodestat!=''){
			return $this->encodestat;
		}
		else{
			return -1;
		}
	}
	function setFileStat(){
		$enctype=$this->getEncoding();
		if($enctype!=''){
			$this->setCodeStat("<?xml version='1.0' encoding='".$enctype."'?>\n");
		}
		else{
			$this->setCodeStat("<?xml version='1.0' encoding='utf-8' ?>\n");
		}

		return 0;
	}

	//DEVUELVE UN ARRAY CON LA ESRUCTURA XML
	function arrGetStructClass(){
		//Armar la escrutctura del XML
		$claseppal=$this->getMainNode();
		//imprimir la clase ppal
		$cant_clases=$this->cant_clases;

		foreach ($this->clases[$claseppal] as $llave=>$valor) {
			$this->estrucxml_arr[]="\t<".$valor.">\n";
				foreach ($this->nodos[$valor] as $key=>$value) {
					if($this->perte[$key]==$valor){
					    $this->estrucxml_arr[]="\t\t<".$key.">".$value."</".$key.">\n";
					}
				}
			$this->estrucxml_arr[]="\t</".$valor.">\n";
		}

		//$directorio
		if($this->estrucxml_arr!=''){
			return $this->estrucxml_arr;
		}
		else{
			return -1;
		}
	}
	//DEVUELVE UN ARRAY CON LA ESRUCTURA XML (SOLO PARTE DE LA ESTRUCTURA)
	function arrGetStructClass_clase(){
		//Armar la escrutctura del XML
		$claseppal=$this->getMainNode();
		//imprimir la clase ppal
		$cant_clases=$this->cant_clases;

		foreach ($this->clases[$claseppal] as $llave=>$valor) {
			$this->estrucxml_arr[]="\t<".$valor.">\n";
				foreach ($this->nodos[$valor] as $key=>$value) {
					if($this->perte[$key]==$valor){
					    $this->estrucxml_arr[]="\t\t<".$key.">".$value."</".$key.">\n";
					}
				}
			$this->estrucxml_arr[]="\t</".$valor.">\n";
		}
		//$directorio
		if($this->estrucxml_arr!=''){
			return $this->estrucxml_arr;
		}
		else{
			return -1;
		}
	}
	//CREA EL ARCHIVO XML FISICO Y PLASMA LA ESTRUCTURA XML EN EL
	function getOutput($option){
		$claseppal=$this->getMainNode();
		//imprimir la clase ppal
		$cant_clases=$this->cant_clases;
		$claseppaltext="<".$claseppal.">\n";
		$claseppaltextc="</".$claseppal.">\n";

		//Armar la escrutctura del XML
		$claseppal=$this->getMainNode();
		//imprimir la clase ppal
		$cant_clases=$this->cant_clases;

		foreach ($this->clases[$claseppal] as $llave=>$valor) {
			$this->estrucxml_arr[]="\t<".$valor.">\n";
				foreach ($this->nodos[$valor] as $key=>$value) {
					if($this->perte[$key]==$valor){
					    $this->estrucxml_arr[]="\t\t<".$key.">".$value."</".$key.">\n";
					}
				}
			$this->estrucxml_arr[]="\t</".$valor.">\n";
		}
		//Armar ARCHIVO XML externo file_put_contents (se ahorran 3 lineas de codigo jejeje)....
		if($this->primeravez!=0){
			$clase=$this->nmclass;
			$dirfile=$this->xmldir;
			$clasppal=$this->getMainNode();
			$contenido=$this->estrucxml_arr;

			if($contenido!='' && $dirfile!=''){
				if($option=='c'){
					$this->etiqcier='c';
					file_put_contents($dirfile, $contenido, FILE_APPEND| LOCK_EX);
					file_put_contents($dirfile, $claseppaltextc, FILE_APPEND| LOCK_EX);
				}
				if($option=='u'){
					file_put_contents($dirfile, $contenido, FILE_APPEND| LOCK_EX);
				}
				if($option=='uf'){
					$this->etiqcier='uf';
					file_put_contents($dirfile, $contenido, FILE_APPEND| LOCK_EX);
					file_put_contents($dirfile, $claseppaltextc, FILE_APPEND| LOCK_EX);
				}
				$this->primeravez++;
			}
			else{
				return -1;
			}
		}
		else{
			$contenido=$this->encodestat;
			//Armar ARCHIVO XML externo file_put_contents (se ahorran 3 lineas de codigo jejeje)....
			$clase=$this->nmclass;
			$dirfile=$this->xmldir;
			$clasppal=$this->getMainNode();
			$contenido=$this->estrucxml_arr;
			$enca=$this->encodestat;
			if($contenido!='' && $dirfile!=''){
				file_put_contents($dirfile,"<?xml version='1.0' encoding='utf-8'?>\n");
				file_put_contents($dirfile, $claseppaltext, FILE_APPEND| LOCK_EX);
				file_put_contents($dirfile, $contenido, FILE_APPEND| LOCK_EX);
				$this->primeravez++;
			}
			else{
				return -1;
			}
		}
		$this->vaciarXML();
	}
	//Codifica una cadena a MD5
	function XMLEncStrMD5($cadena){
		$this->mdcstr=md5($cadena);
		return $this->mdcstr;
	}

	function vaciarXML(){
		$this->valores=array();
		$this->clases=array();
		$this->class_ppal=array();
		$this->nodos=array();
		$this->perte=array();
		$this->estrucxml_arr=array();
		$cant_clases=0;
		$cant_nodos=0;
		$i=0;
		$u=0;
	}
	function XMLgetError(){
		if($this->etiqcier==''){
			$error="<h3>No existe etiqueta de cierre FINAL.</h3>";
			return $error;
		}
	}
	//Manejador de Base de datos ARTXML
	//funcion que "conecta" con archivo XML
	public function setNameXML($rutarchivo){
		if(file_exists($rutarchivo)){
			$this->archivofis=$rutarchivo;
			return 0;
		}
		else{
			return -1;
		}
	}
	public function getNameXML(){
		if($this->archivofis!=''){
			return $this->archivofis;
		}
		else{
			return -1;
		}
	}
	//obtiene elementos por el ID del objeto
	function getElementByID($IDetiqueta){
		$coinc=array();
		$nombrearch=$this->getNameXML();
		if(!($archivo = fopen($nombrearch,"r"))){
	   		return -1;
		}
		else{
			$i=0;
			while($linea=fgets($archivo,1024)){
				/* Remover espacios en blanco de la cadena: */
				$linea = trim($linea);
				/* Remover etiquetas HTML: */
				$modelo="/<(.*?) id=\"".$IDetiqueta."\" [^>]*>(.*?)<\/(.*?)>/";
				preg_match_all($modelo,$linea,$palabras);
				if($palabras[1][0]!=''){
					$coinc[$i]=$palabras[1][0];
					$i++;
				}
				else{
					$modelo="/<(.*?) id=\"".$IDetiqueta."\" [^>]*>(.*?)<\/(.*?)>/";
					preg_match_all($modelod,$linea,$palabras);
					if($palabras[1][0]!=''){
						$coinc[$i]=$palabras[1][0];
						$i++;
					}
					else{
						$modelo="/<(.*?) id=\"".$IDetiqueta."\" [^>]*>(.*?)<\/(.*?)>/";
						preg_match_all($modelo,$linea,$palabras);
						if($palabras[1][0]!=''){
							$coinc[$i]=$palabras[1][0];
							$i++;
						}
					}
				}
			}
			fclose($archivo);
			$valores=$i;
			if($valores<2){
				$string=$coinc[0];
				return $string;
			}
			else{
				return $coinc;
			}
		}
	}
	//obtiene elementos por la clase del objeto
	function getElementByClass($Clasetiqueta){
		$coinc=array();
		$nombrearch=$this->getNameXML();
		if(!($archivo = fopen($nombrearch,"r"))){
	   		return -1;
		}
		else{
			$i=0;
			while($linea=fgets($archivo,1024)){
				/* Remover espacios en blanco de la cadena: */
				$linea = trim($linea);
				/* Remover etiquetas HTML: */
				$modelo="/<(.*?) class=\"".$Clasetiqueta."\" [^>]*>(.*?)<\/(.*?)>/";
				preg_match_all($modelo,$linea,$palabras);
				if($palabras[1][0]!=''){
					$coinc[$i]=$palabras[1][0];
					$i++;
				}
				else{
					$modelo="/<(.*?) class=\"".$Clasetiqueta."\" [^>]*>(.*?)<\/(.*?)>/";
					preg_match_all($modelod,$linea,$palabras);
					if($palabras[1][0]!=''){
						$coinc[$i]=$palabras[1][0];
						$i++;
					}
					else{
						$modelo="/<(.*?) class=\"".$Clasetiqueta."\" [^>]*>(.*?)<\/(.*?)>/";
						preg_match_all($modelo,$linea,$palabras);
						if($palabras[1][0]!=''){
							$coinc[$i]=$palabras[1][0];
							$i++;
						}
					}
				}
			}
			fclose($archivo);
			$valores=$i;
			if($valores<2){
				$string=$coinc[0];
				return $string;
			}
			else{
				return $coinc;
			}
		}
	}
	function getElementsByTagName($etiqueta){
		$coinc=array();
		//obtener numero de argumentos
	    $numargs = func_num_args();
	    $argulist = func_get_args();
		$indice_array_=0;
		if($numargs>1){
			$indice_array_=$argulist[1];
		}
		$nombrearch=$this->getNameXML();
		if(!($archivo = fopen($nombrearch,"r"))){
	   		return -1;
		}
		else{
			$i=0;
			while($linea=fgets($archivo,1024)){
				/* Remover espacios en blanco de la cadena: */
				$linea = trim($linea);
				/* Remover etiquetas HTML: */
				$modelo="/<".$etiqueta."[^>]*>(.*?)<\/".$etiqueta.">/";
				//$modelo="/<(.*?)>".$valbus."<(.*?)>/";
				preg_match_all($modelo,$linea,$palabras);
				if($palabras[1][0]!=''){
					$coinc[$i]=$palabras[1][0];
					$i++;
				}
				else{
					$modelod="/<(".$etiqueta.")>/";
					preg_match_all($modelod,$linea,$palabras);
					if($palabras[1][0]!=''){
						$coinc[$i]=$palabras[1][0];
						$i++;
					}
					else{
						$modelo="/<(".$etiqueta.")[^>]*>(.*?)<\/(".$etiqueta.")>/";
						preg_match_all($modelo,$linea,$palabras);
						if($palabras[1][0]!=''){
							$coinc[$i]=$palabras[1][0];
							$i++;
						}
					}
				}
			}
			fclose($archivo);
			$valores=$i;
			if($valores<2){
				$string=$coinc[0];
				return $string;
			}
			else{
				if($indice_array_>0){
					if($indice_array_<=count($coinc)){
						return $coinc[$indice_array_];						
					}
				}
				else{
					$string=$coinc[0];
					return $string;
				}
			}
		}
	}
	function getTagByValueName($valbus){
		$coinval=array();
		$nombrearch=$this->getNameXML();
		if(!($archivo = fopen($nombrearch,"r"))){
	   		return -1;
		}
		else{
			$i=0;
			while($linea=fgets($archivo,1024)){
				/* Remover espacios en blanco de la cadena: */
				$linea = trim($linea);
				/* Remover etiquetas HTML: */
				$modelo="/<(.*?)>".$valbus."<(.*?)>/";
				preg_match_all($modelo,$linea,$palabras);
				if($palabras[1][0]!=''){
					$coinval[$i]=$palabras[1][0];
					$encontrado=1;
					$i++;
				}
				else{
					$modelod="/<".$valbus."[^>]*>/";
					preg_match_all($modelod,$linea,$palabras);
					if($palabras[1][0]!=''){
						$coinc[$i]=$palabras[1][0];
						$i++;
					}
				}
			}
			fclose($archivo);

			if($encontrado!=1){
				return -1;
			}
			else{
				return $coinval;
			}
		}
	}
	//cambiar valor a etiquetas unicas
	function ReplaceData($etiqueta,$valorrep){
		$coinc=array();
		$nombrearch=$this->getNameXML();
		if(!($archivo = fopen($nombrearch,"r+"))){
	   		return -1;
		}
		else{
			$archxml=file($nombrearch);
			$i=0;
			while($linea=fgets($archivo,1024)){
				/* Remover espacios en blanco de la cadena: */
				$linea = trim($linea);
				/* Remover etiquetas HTML: */
				$modelo="/<".$etiqueta."[^>]*>(.*?)<\/".$etiqueta.">/";
				preg_match($modelo,$linea,$palabras);
				$palab=$palabras[0];
				if($palab!=''){
					$string = $linea;
					$pattern = "/<".$etiqueta."[^>]*>(.*?)<\/".$etiqueta.">/";
					$replacement = "<".$etiqueta.">".$valorrep."</".$etiqueta.">";
					$rep=preg_replace($pattern, $replacement, $string);
					$i++;
				}
			}

			$u=count($archxml);
			for($i=0;$i<=($u-1);$i++){
				$modelo="/<".$etiqueta."[^>]*>(.*?)<\/".$etiqueta.">/";
				$linea=$archxml[$i];
				$linea=trim($linea);
				preg_match($modelo,$linea,$palabras);
				if($palabras[0]!=''){
					$pattern = "/<".$etiqueta."[^>]*>(.*?)<\/".$etiqueta.">/";
					$replacement = "\t\t\t<".$etiqueta.">".$valorrep."</".$etiqueta.">\n";
					$rep=preg_replace($pattern, $replacement, $string);
					$archxml[$i]=$rep;
				}
			}

			file_put_contents($nombrearch,$archxml);
			fclose($archivo);
			return 0;
		}
	}
	function AppendData($arbol){
		$archivo_arr=array();
		$brakeline='';
		$count_file=0;
		$cadena="";
		$i=0;
		$archivo=$this->getNameXML();
		if($archivo!='' && $arbol!=''){
			if(file_exists($archivo)){
				if($fpuntero=fopen($archivo, "r")){
					while(!feof($fpuntero)){
					    //read file line by line into a new array element
					    $archivo_arr[]=fgets($fpuntero, 4096);
					    $i++;
					}
				}
				fclose($fpuntero);
				$count_file=count($archivo_arr);
				$archivo_arr[($count_file-1)]="";
				if($fpuntero=fopen($archivo, "w")){
					file_put_contents($archivo, $archivo_arr);
				}
				fclose($fpuntero);
				if($fpuntero=fopen($archivo, "a")){
					fwrite($fpuntero,$arbol);
				}
				fclose($fpuntero);
			}
			else{
				return -2;
			}
		}
		else{
			return -1;
		}
	}
	function CreateFile($directorio,$contenido){
		if(!file_exists($directorio)){
			$archivo=fopen($directorio,"w+");
			if($archivo){
				fwrite($archivo,$contenido);
				fclose($archivo);
				return 0;
			}
			else{
				return -1;
			}
		}
		else{
			return -2;
		}
	}
	function CreateXML($directorio,$contenido){
		if(!file_exists($directorio)){
			$archivo=fopen($directorio,"w+");
			if($archivo){
				$this->setCodeStat("<?xml version='1.0' encoding='utf-8'?>\n");
				fwrite($archivo,$this->getCodeStat());
				fwrite($archivo,$contenido);
				fclose($archivo);
				return 0;
			}
			else{
				return -1;
			}
		}
		else{
			return -2;
		}
	}
	function CreatePHP($directorio,$contenido){
		if(!file_exists($directorio)){
			$archivo=fopen($directorio,"w+");
			if($archivo){
				/*CABEZA Y PIE*/
				$cabeza="<?php\n";
				$pie="?>\n";
				/**************/
				fwrite($archivo,$cabeza);
				fwrite($archivo,$contenido);
				fwrite($archivo,$pie);
				fclose($archivo);
				return 0;
			}
			else{
				return -1;
			}
		}
		else{
			return -2;
		}
	}
	function CreatePHPConfig($directorio,$opcion){
		if($opcion=='u'){
				$archivo=fopen($directorio,"w");
				if($archivo){
					/*CABEZA Y PIE*/
					$cabeza="<?php\n";
					/**************/
					fwrite($archivo,$cabeza);
					for($i=0;$i<=(count($this->archivoconf)-1);$i++){
						$string=$this->archivoconf[$i]."\n";
						fwrite($archivo,$string);
					}
					$pie="?>\n";
					fwrite($archivo,$pie);
					fclose($archivo);
					return 0;
				}
				else{
					return -1;
				}
		}
		else{
			if(!file_exists($directorio)){
				$archivo=fopen($directorio,"w+");
				if($archivo){
					/*CABEZA Y PIE*/
					$cabeza="<?php\n";
					/**************/
					fwrite($archivo,$cabeza);
					for($i=0;$i<=(count($this->archivoconf)-1);$i++){
						$string=$this->archivoconf[$i]."\n";
						fwrite($archivo,$string);
					}
					$pie="?>\n";
					fwrite($archivo,$pie);
					fclose($archivo);
					return 0;
				}
				else{
					return -1;
				}
			}
			else{
				return -2;
			}
		}
	}
	function CreateCSS($directorio,$contenido){
		if(!file_exists($directorio)){
			$archivo=fopen($directorio,"w+");
			if($archivo){
				fwrite($archivo,$contenido);
				fclose($archivo);
				return 0;
			}
			else{
				return -1;
			}
		}
		else{
			return -2;
		}
	}
	function LabelToDefineArray($etiqueta,$def){
		$nombre=$this->getElementByTagName($etiqueta);
		$n=count($nombre);
		if($nombre==''){
			return -2;
		}
		else{
			$str_PHP="define('".$def."','".$nombre."');";
			$this->archivoconf[]=$str_PHP;
			return $str_PHP;
		}
	}
	function LabelToDefineStr($valor,$def){
		$str_PHP="define('".$def."','".$valor."');";
		$this->archivoconf[]=$str_PHP;
		return $str_PHP;
	}
	function setTreeConfig(){
		$valor=array();$item=array();
		/*diferenciar el lado derecho del lado izq porque el lado izquiero esta en mayusculas
		y el lado derecho en minusculas */
		$numero=func_num_args();
		$argu=func_get_args();
		$nombrearbol=$argu[0];
		$modelo="/^nom_[a-z]*/";
		//$modelo2="/^i_[a-z]*/";
		//$modelo3="/^v_[a-z]*/";
		$modelonum="/^[0-9]*/";
		preg_match($modelo,$nombrearbol,$coinc);
		if($coinc!=''){
			for($i=1;$i<=(count($argu)-1);$i++){
				$str1=$argu[$i];
				$str2=strtoupper($argu[$i]);
				preg_match($modelonum,$str2,$numeco);
				if($str2==$str1 && $numeco[0]==''){
					$item[]=$argu[$i];
				}
				elseif($str2==$str1 && $numeco[0]!=''){
					$valor[]=$argu[$i];
				}
				else{
					if($str2!=$str1){
						$valor[]=$argu[$i];
					}
				}
			}
			$valores=((($numero-1)/2));
			$nombreqs=$coinc[0];
			$nombres=explode('_',$nombreqs);
			$result="\$conf['".$nombres[1]."'](\n";
			for($i=0;$i<=$valores;$i++){
				if($i!=$valores && $i!=$valores-1){
					$result.="\t'".$item[$i]."'=>'".$valor[$i]."',\n";
				}
				elseif($i==($valores-1)){
					$result.="\t'".$item[$i]."'=>'".$valor[$i]."'\n";
				}
				else{
					$result.=");\n\n\n";
				}
			}
			$this->archivoconf[]=$result;
			return $result;
		}
		else{
			return -1;
		}
	}

	// Abre un directorio conocido, y procede a leer el contenido
	function AnalizeDir(){
		$directorio=$this->getNameXML();
		$i=0;
		$archivosnom='';
		if (is_dir($directorio)) {
		    if ($dh = opendir($directorio)) {
		        while (($file = readdir($dh)) !== false) {
					if($file!='.' || $file!='..' || $file!='...'){
						$archivosnom[$i]=$file;
					}
			        $i++;
		        }
		        closedir($dh);
		    }
		}
		else{
			die("El directorio no existe");
		}
		return $archivosnom;
	}

	function ListFileMulti(){
		$directorio=$this->getNameXML();
		if($directorio!=''){
			$i=0;
			$narchivos=0;
			if (is_dir($directorio)) {
			    if ($dh = opendir($directorio)) {
			        while (($file = readdir($dh)) !== false) {
						$narchivos++;
			        }
			        closedir($dh);
			    }
			}
			else{
				die("El directorio no existe");
			}
			return $narchivos;
		}
		else{
			return -1;
		}
	}

	function ListFileSingle(){
		$directorio=$this->getNameXML();
		if($directorio!=''){
			$i=0;
			if (is_dir($directorio)) {
			    if ($dh = opendir($directorio)) {
			    	echo("Nombre del Archivo "."|Directorio: $directorio "."<br>");
			    	echo("-------------------------------------------------------<br>");
			        while (($file = readdir($dh)) !== false) {
			        	if(strlen($file)>2){
			        		echo($directorio.$file."<br>");
			        	}
			            $i++;
			        }
			        closedir($dh);
			        return 0;
			    }
			}
		}
		else{
			return -1;
		}
	}

	function setModuloDir($direc){
		//setear el directorio de donde se va a hacer el menu
		if(is_dir($direc)){
			$this->menudir=$direc;
			return 0;
		}
		else{
			return -1;
		}
	}
	function getModuloDir(){
	//obtener el directorio de donde se va a hacer el menu
		if($this->menudir!=''){
			return $this->menudir;
		}
		else{
			return -1;
		}
	}
	function VerModulos(){
		$coinc=array();
		$modulos=array();
		//si opcion=0 entonces es simple, si opcion=1 entonces es un menu completo con niveles
		$directorio=$this->getModuloDir();
		if($directorio!=''){
			$directotal=$directorio.'modulosall_actual.xml';
			$this->setNameXML($directotal);
			$res=$this->getElementByTagName('numero_modulos');
			return $res;
		}
	}
	function ActualizarModulos(){
		//Función que actualiza el archivo que lista los módulos que exisdten en el sistema
		$coinc=array();
		$modulos=array();
		$directorio=$this->getModuloDir();
		if($directorio!=''){
			$i=0;
			if(is_dir($directorio)){
				$i=0;
				$narchivos=0;
				if(is_dir($directorio)){
				    if($dh = opendir($directorio)){
				    	$directotal=$directorio.'modulosall_actual.xml';
						$myclas= new xmlclass();
						$myclas->XMLDocNew($directotal);
						$myclas->MkMainNode('sistema');
						//Crear las clases de la estructura XML
						//////////////////////////////
						//Establecer la clase Persona
						//u= indica que es la ultima etiqueta de la clase
						//uf=indica que es la ultima clase
						//Leer el direcotrio especificado
				        while (($file = readdir($dh)) !== false) {
					        if(preg_match_all("/^([A-Za-z]*)(.php|.html|.xhtml|.php3)/",$file,$coinc)){
					        	echo($coinc[0][0]);
					        	$modulos[]=$file;
								$narchivos++;
					        }
				        }
				        closedir($dh);
						$myclas->setClase('modulos');
				        for($i=0;$i<=($narchivos-1);$i++){
				        	$cadena='modulo'.$i;
				        	if($i==($narchivos-1)){
				        		$myclas->setSubNode($cadena,$modulos[$i],'u');
				        	}
				        	else{
								$myclas->setSubNode($cadena,$modulos[$i]);
				        	}
				        }
				        $myclas->setClase('modulos_info');
				        $myclas->setSubNode("numero_modulos",$narchivos,'uf');
						//Crear las clases de la estructura XML
						//////////////////////////////
						//Establecer la clase Persona
						//u= indica que es la ultima etiqueta de la clase
						//uf=indica que es la ultima clase
						return 0;
				    }
				    else{
				    	return -3;
				    }
				}
				else{
					die("El directorio no existe");
				}
			}
		}
		else{
			return -4;
		}
	}
	function getIntoFolder($menu){
		$coinc=array();
		$divis=array();
		$directorio=$this->getModuloDir();
		if($directorio!=''){
			if($this->seekParentMenu($menu)==0){
				//Contar los archivos que hay en el direcotrio especificado
				//colocar un ultimo caracter que defina si el archivo va al menu o no
				$directorio_dos = opendir($directorio);
				$modelo='/^[A-Za-z0-9_-]{0,64}_[A-Za-z0-9_-]{0,64}_[a-z]{1}.php/';
				$numarchivos=0;
				while ($archivo = readdir($directorio_dos)){
					if($archivo!='.' || $archivo!='..'){
						if(preg_match_all($modelo,$archivo,$coinc)){
							$numarchivos++;
						}
					}
				}
				//crear el menu :):):):):-P
				$directorio_dos = opendir($directorio);
				$i=0;
				while ($archivo = readdir($directorio_dos)){
					if(preg_match_all($modelo,$archivo,$coinc)){
						$divis=explode('_',$coinc[0][0]);
						$divdos=explode('.',$divis[2]);
						$cadena=$divis[0];
						$id=$divis[2];
						if($divdos[0]=='m' || $divdos[0]=='a' || $divdos[0]=='t'){
							$cadena=str_replace('-', ' ', $cadena);
							$cadena=ucwords($cadena);
							$this->MenuAppendChild($menu,$divis[0],$divis[1]);
						}
						$i++;
					}
				}
			}
			else{
				return -2;
			}
		}
		else{
			return -1;
		}
	}
	public function sanitize_identity($cadena){
        return str_replace(array("á","é","í","ó","ú","ñ","Á","É","Í","Ó","Ú","Ñ"),array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&ntilde;","&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","&Ntilde;"), $cadena);
    }
	public function sanitize_tags($cadena){
        return strip_tags($cadena);
    }
	public function sanitize_slashes($cadena){
        return stripslashes(stripslashes($cadena));
    }
	public function sanitize_whitespace($cadena){
        return php_strip_whitespace($cadena);
    }
	public function sanitize($cadena){
        return $this->sanitize_tags($this->sanitize_slashes($this->sanitize_identity($cadena)));
    }
	public function space2lines($cadena){
        return str_replace(" ", "-", $cadena);
    }
}
?>
