<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔎 Probando conexión OCI8 a Oracle...</h2>";

$username = "APP_RNPMOVIL";
$password = "jT?hdmLRM&|-5z'R121V~";
$connection_string = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=10.20.41.107)(PORT=1521))(CONNECT_DATA=(SERVICE_NAME=PROD_APP.PHXNETBDPRODSUB.PHXVCNAPPMOVILR.ORACLEVCN.COM)))";

$conn = @oci_connect($username, $password, $connection_string, 'AL32UTF8');

if (!$conn) {
    $e = oci_error();
    echo "<b>❌ Error de conexión:</b><br>";
    echo htmlentities($e['message'], ENT_QUOTES);
} else {
    echo "<b>✅ Conexión exitosa a Oracle.</b><br>";

    // Probar una consulta simple
    $sql = "SELECT SYSDATE FROM dual";
    $stid = oci_parse($conn, $sql);
    oci_execute($stid);
    $row = oci_fetch_assoc($stid);
    echo "<p>📅 Fecha actual en Oracle: " . $row['SYSDATE'] . "</p>";

    oci_close($conn);
}
