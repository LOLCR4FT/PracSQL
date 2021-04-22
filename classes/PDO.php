<?php
class PDO2
{
    private $con;
    private $dsn = 'mysql:dbname=alums;host=127.0.0.1';
    private $num = 2; //numero que correspon a la array de posibles seleccions
    private $user = 'root';
    private $password = '';

    function __construct()
    {
        try {
            $this->con = new PDO($this->dsn, $this->user, $this->password);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            print_r($this->con->errorInfo());
        }
    }

    function getOne($id)
    {
        if ($stmt = $this->con->prepare("SELECT nom,data_naix FROM autor WHERE id=:ID")) {

            /* ejecutar la consulta ligar variables de resultado*/
            $stmt->execute(array(':ID' => $id));

            /* obtener valor */
            $result = $stmt->fetchAll();
            if ($result) {
                return $result[0];
            }

            return null;
        }
    }

    function showAutors()
    {
        $sql = 'SELECT * FROM autor';
        $statement = $this->con->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();

        echo "<table border='1'>";
        echo "<tr>";
        echo "<td>id</td><td>nom</td><td>data_naix</td>";
        echo "<td>Elimina</td><td>Edita</td><td>Filtra</td>";
        echo "</tr>";

        for ($i = 0; $i < count($result); $i++) {
            echo "<tr>";
            echo "<td>" . $result[$i]['id'] . "</td>";
            echo "<td>" . $result[$i]['nom'] . "</td>";
            echo "<td>" . $result[$i]['data_naix'] . "</td>";
            echo "<td><a href='http://localhost/conectors?used=" . $this->num . "&dA=" . $result[$i]['id'] . "'>Elimina</a></td><td><a href='http://localhost/conectors?used=" . $this->num . "&eA=" . $result[$i]['id'] . "'>Editar</a></td>
            <td><a href='http://localhost/conectors?used=" . $this->num . "&autor=" . $result[$i]['id'] . "'>Filtra</a></td>
            ";
            echo "</tr>";
        }
        echo "</table>";
    }

    function deleteAutor($id)
    {
        if ($stmt = $this->con->prepare("DELETE FROM autor where id=:ID")) {

            /* ligar parámetros para marcadores */
            $stmt->bindParam(':ID', $id);
            $stmt->execute();
        }
    }

    function editAutor($data)
    {

        $stmt = $this->con->prepare("update autor set nom=:nom, data_naix=:dnaix where id=:id");

        $stmt->execute(array(':nom' => $data['nom'], ':dnaix' => $data['data_naix'], ':id' => $data['id']));
    }

    function insertAutor($data)
    {
        $stmt = $this->con->prepare("insert into autor(nom,data_naix) values(:nom,:dnaix)");

        $stmt->execute(array(':nom' => $data['nom'], ':dnaix' => $data['data_naix']));
    }

    function showLlibres()
    {
        echo "<table border='1'>";
        echo "<tr>";
        echo "<td>id</td><td>id_autor</td><td>titol</td><td>any</td>";
        echo "<td>Elimina</td><td>Edita</td>";
        echo "</tr>";

        $sql = 'SELECT * FROM llibre';
        $statement = $this->con->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();

        for ($i = 0; $i < count($result); $i++) {
            echo "<tr>";
            echo "<td>" . $result[$i]['id'] . "</td>";
            echo "<td>" . $result[$i]['id_autor'] . "</td>";
            echo "<td>" . $result[$i]['titol'] . "</td>";
            echo "<td>" . $result[$i]['any'] . "</td>";
            echo "<td><a href='http://localhost/conectors?used=" . $this->num . "&dL=" . $result[$i]['id'] . "'>Elimina</a></td><td><a href='http://localhost/conectors?used=" . $this->num . "&eL=" . $result[$i]['id'] . "'>Editar</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    function selectAutor($autor)
    {
        echo "<select name='autors'>";
        $sql = 'select id,nom from autor';
        $statement = $this->con->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        for ($i = 0; $i < count($result); $i++) {
            if ($result[$i]['id'] == $autor) {
                echo "<option value='" . $result[$i]['id'] . "' selected>" . $result[$i]['nom'] . "</option>";
            } else {
                echo "<option value='" . $result[$i]['id'] . "'>" . $result[$i]['nom'] . "</option>";
            }
        }
        echo "</select>";
    }

    function getLlibre($id)
    {
        if ($id) {
            $stmt = $this->con->prepare("SELECT id_autor as autor,titol,any FROM llibre WHERE id=:id");

            $stmt->execute(array(':id' => $id));

            $result = $stmt->fetchAll();
            if ($result) return $result[0];
        }
        return null;
    }

    function showLLibreAutor($id)
    {
        if ($id) {
            $stmt = $this->con->prepare("SELECT * FROM llibre where id_autor=:id");
            $stmt->execute(array(':id' => $id));

            $result = $stmt->fetchAll();

            echo "<table border='1'>";
            echo "<tr><td>id</td><td>id_autor</td><td>titol</td><td>any</td><td>Elimina</td><td>Edita</td></tr>";
            for ($i = 0; $i < count($result); $i++) {
                echo "<tr><td>" . $result[$i]['id'] . "</td><td>" . $result[$i]['id_autor'] . "</td><td>" . $result[$i]['titol'] . "</td><td>" . $result[$i]['any'] . "</td><td><a href='http://localhost/conectors?used=" . $this->num . "&dL=" . $result[$i]['id'] . "'>Elimina</a></td><td><a href='http://localhost/conectors?used=" . $this->num . "&eL=" . $result[$i]['id'] . "'>Editar</a></td></tr>";
            }
            echo "<tr><td colspan='6' class='centrar'><a href='http://localhost/conectors/?used=" . $this->num . "'>Reset</a></td></tr>";
            echo "</table>";
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
