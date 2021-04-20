<?php
class sqlsrv
{
    private $con;
    private $num = 4;
    function __construct()
    {
        $serverName = "localhost\\sqlexpress"; //serverName\instanceName

        // Since UID and PWD are not specified in the $connectionInfo array,
        // The connection will be attempted using Windows Authentication.
        $connectionInfo = array( "Database"=>"master");
        $this->con = sqlsrv_connect( $serverName, $connectionInfo);
        
        if( $this->con) {
             echo "Connection established.<br />";
        }else{
             echo "Connection could not be established.<br />";
             die( print_r( sqlsrv_errors(), true));
        }
    }

    function getOne($id)
    {
        if ($stmt = $this->con->prepare("SELECT nom,data_naix FROM autor WHERE id=?")) {

            /* ligar parámetros para marcadores */
            $stmt->bind_param("i", $id);

            /* ejecutar la consulta */
            $stmt->execute();

            /* ligar variables de resultado */
            $stmt->bind_result($col1, $col2);

            /* obtener valor */
            $stmt->fetch();

            /* cerrar sentencia */
            $stmt->close();

            $result = array(
                'nom' => $col1,
                'data_naix' => $col2
            );

            return $result;
        }
    }

    function showAutors()
    {
        $result = $this->con->query("select * from autor");
        $field_info = $result->fetch_fields();
        echo "<table border='1'>";
        echo "<tr>";
        foreach ($field_info as $field) {
            echo "<td>" . $field->name . "</td>";
        }
        echo "<td>Elimina</td><td>Edita</td>";
        echo "</tr>";

        while ($row = $result->fetch_row()) {
            echo "<tr>";
            for ($i = 0; $i < count($field_info); $i++) {
                echo "<td>" . $row[$i] . "</td>";
            }
            echo "<td><a href='http://localhost/conectors?used=" . $this->num . "&dA=" . $row[0] . "'>Elimina</a></td><td><a href='http://localhost/conectors?used=" . $this->num . "&eA=" . $row[0] . "'>Editar</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    function deleteAutor($id)
    {
        if ($stmt = $this->con->prepare("DELETE FROM autor where id=?")) {

            /* ligar parámetros para marcadores */
            $stmt->bind_param("i", $id);
            /* ejecutar la consulta */
            $stmt->execute();
            /* cerrar sentencia */
            $stmt->close();
        }
    }

    function editAutor($data)
    {

        $stmt = $this->con->prepare("update autor set nom=?, data_naix=? where id=?");

        $stmt->bind_param("ssi", $data['nom'], $data['data_naix'], $data['id']);
        $stmt->execute();
    }

    function insertAutor($data)
    {
        $stmt = $this->con->prepare("insert into autor(nom,data_naix) values(?,?)");

        $stmt->bind_param("ss", $data['nom'], $data['data_naix']);
        $stmt->execute();
    }
}
