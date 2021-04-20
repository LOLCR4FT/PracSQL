<?php
class Mysqli2
{
    private $con;
    private $num = 0;
    function __construct()
    {
        $this->con = new mysqli("localhost", "root", "", "alums");
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
        echo "<td>Elimina</td><td>Edita</td><td>Filtra</td>";
        echo "</tr>";

        while ($row = $result->fetch_row()) {
            echo "<tr>";
            for ($i = 0; $i < count($field_info); $i++) {
                echo "<td>" . $row[$i] . "</td>";
            }
            echo "<td><a href='http://localhost/conectors?used=" . $this->num . "&dA=" . $row[0] . "'>Elimina</a></td><td><a href='http://localhost/conectors?used=" . $this->num . "&eA=" . $row[0] . "'>Editar</a></td>
            <td><a href='http://localhost/conectors?used=" . $this->num . "&autor=" . $row[0] . "'>Filtra</a></td>
            ";
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

    function showLlibres()
    {
        $result = $this->con->query("select * from llibre");
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
            echo "<td><a href='http://localhost/conectors?used=" . $this->num . "&dL=" . $row[0] . "'>Elimina</a></td><td><a href='http://localhost/conectors?used=" . $this->num . "&eL=" . $row[0] . "'>Editar</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    function selectAutor($autor)
    {
        echo "<select name='autors'>";
        $result = $this->con->query("select id,nom from autor");
        while ($row = $result->fetch_row()) {
            if ($row[0] == $autor) {
                echo "<option value='" . $row[0] . "' selected>" . $row[1] . "</option>";
            } else {
                echo "<option value='" . $row[0] . "'>" . $row[1] . "</option>";
            }
        }
        echo "</select>";
    }

    function getLlibre($id)
    {
        if ($stmt = $this->con->prepare("SELECT id_autor,titol,any FROM llibre WHERE id=?")) {

            /* ligar parámetros para marcadores */
            $stmt->bind_param("i", $id);

            /* ejecutar la consulta */
            $stmt->execute();

            /* ligar variables de resultado */
            $stmt->bind_result($col1, $col2, $col3);

            /* obtener valor */
            $stmt->fetch();

            /* cerrar sentencia */
            $stmt->close();

            $result = array(
                'autor' => $col1,
                'titol' => $col2,
                'any' => $col3
            );

            return $result;
        }
    }

    function showLLibreAutor($id)
    {
        if ($id) {
            $stmt = $this->con->prepare("SELECT * FROM llibre where id_autor=?");
            /* ligar parámetros para marcadores */
            $stmt->bind_param("i", $id);
            /* ejecutar la consulta */
            $stmt->execute();
            /* ligar variables de resultado */
            $result = $stmt->get_result();
            echo "<table border='1'>";
            echo "<tr><td>id</td><td>id_autor</td><td>titol</td><td>any</td><td>Elimina</td><td>Edita</td></tr>";
            while ($row = $result->fetch_row()) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td><td><a href='http://localhost/conectors?used=" . $this->num . "&dL=" . $row[0] . "'>Elimina</a></td><td><a href='http://localhost/conectors?used=" . $this->num . "&eL=" . $row[0] . "'>Editar</a></td></tr>";
            }
            echo "<tr><td colspan='6' class='centrar'><a href='http://localhost/conectors/?used=" . $this->num . "'>Reset</a></td></tr>";
            echo "</table>";

            /* cerrar sentencia */
            $stmt->close();
        }
    }

    function insertLlibre($data)
    {
        $stmt = $this->con->prepare("insert into llibre(id_autor,titol,any) values(?,?,?)");

        $stmt->bind_param("isi", $data['id_autor'], $data['titol'], $data['any']);
        $stmt->execute();
    }

    function editLlibre($data)
    {
        $stmt = $this->con->prepare("update llibre set id_autor=?, titol=?, any=? where id=?");

        $stmt->bind_param("ssii", $data['id_autor'], $data['titol'], $data['any'], $data['id']);
        $stmt->execute();
    }
}
