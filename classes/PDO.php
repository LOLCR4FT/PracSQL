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
        echo "<td>Elimina</td><td>Edita</td>";
        echo "</tr>";

        for ($i = 0; $i < count($result); $i++) {
            echo "<tr>";
            echo "<td>" . $result[$i]['id'] . "</td>";
            echo "<td>" . $result[$i]['nom'] . "</td>";
            echo "<td>" . $result[$i]['data_naix'] . "</td>";
            echo "<td><a href='http://localhost/conectors?used=" . $this->num . "&dA=" . $result[$i]['id'] . "'>Elimina</a></td><td><a href='http://localhost/conectors?used=" . $this->num . "&eA=" . $result[$i]['id'] . "'>Editar</a></td>";
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
}
