<?php
class ODBC2
{
    private $con;
    private $num = 0;
    private $dsn = "pracConnectors";
    private $username = "root";
    private $password = "";
    function __construct()
    {
        $this->con = odbc_connect($this->dsn, $this->username, $this->password);
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

        $sql = "select * from autor";
        $results = odbc_exec($this->con, $sql);

        echo "<table border='1'>";
        echo "<tr>";
        echo "<td>id</td><td>nom</td><td>data_naix</td>";
        echo "<td>Elimina</td><td>Edita</td>";
        echo "</tr>";

        while ($row = odbc_fetch_array($results)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td><td>" . utf8_encode($row['nom']) . "</td><td>" . $row['data_naix'] . "</td>";
            echo "<td><a href='http://localhost/conectors?used=" . $this->num . "&dA=" . $row['id'] . "'>Elimina</a></td><td><a href='http://localhost/conectors?used=" . $this->num . "&eA=" . $row['id'] . "'>Editar</a></td>";
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
