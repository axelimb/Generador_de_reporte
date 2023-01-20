<?php  
require_once __DIR__ . '/vendor/autoload.php';
$servidor = "localhost";
$dbname= "postgres";
$usuario = "postgres";
$contraseña = "123456789";
$idExp = "";
$idfinal = "";
if ($_GET) {
    $idExp = $_GET["id"];
    $caracteres = Array(".",",");
    $resultado = str_replace($caracteres,"",$idExp);
    $idfinal = $resultado;
}
$conexion = pg_connect("host=$servidor port=5432 dbname=$dbname user=$usuario password=$contraseña");
$sql = "SELECT id_expediente,tipo_expediente,expediente,descripcion,archivo_expediente,
estado,alta,titulo,periodo,nro_expediente,nro_nota,procedencia,asunto,usuario,fecha,cedula_buscar
cedula,nombre,apellido,correo,telefono,pin_qr,hora,tipo_origen,tipo_documento,id_dependencia,
observacion,id_dependencia_desde,objeto,id_dependencia_destino,proceso_posterior,caratula   from expedientes where id_expediente = ".$idfinal;
$resultado = pg_exec($conexion,$sql);
$html = '
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Reporte</title>
</head>
<body>
<div id="fondo3">
<img src="img/Merged_document.001.jpeg" alt="imagen1" id="img2">
</div>
<div id="fondo">
<img src="img/Merged_document.002.png" id="img1">
<h2>PRESIDENCIA DE LA REPÚBLICA</h2>
<h2>SECRETARÍA NACIONAL DE DEPORTES</h2>
<h2>AÑO: 2022</h2>';
    while ($var = pg_fetch_array($resultado)){
        $html.='
<h2 id="exc">Expediete: '.$var["id_expediente"].'</h2>
<h2>FECHA: '.$var["fecha"].'</h2>
<h2>RESOLUCION N° '.$var["resolucion"].'</h2> 
<h2>NOTA N° '.$var["nro_nota"].'</h2> <br>
<h2>PROCEDENCIA N° '.$var["procedencia"].'</h2>
<div id="asunto" >
    <h2 >ASUNTO: '.$var["asunto"].'</h2>
</div>';
    }
$html.='
</div>
</body>
';
try {
$mpdf = new \Mpdf\Mpdf(['format' => 'Legal']);
$mpdf->useSubstitutions = false; 
$css= file_get_contents('style.css');
$mpdf->writeHTML($css,1);
$mpdf->WriteHTML($html);
$mpdf->Output("Reporte.pdf","D");
} catch (\Mpdf\MpdfException $e) { 
    echo $e->getMessage();
}
?>