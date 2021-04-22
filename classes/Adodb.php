<?php
include 'adodb5/adodb.inc.php';
include 'adodb5/tohtml.inc.php';
class Adodb2
{
    private $con;
    private $num = 1;
    function __construct()
    {
        $this->con = ADONewConnection('mysql'); # eg 'mysql' or 'postgres'
        $this->con->Connect('localhost', 'root', '', 'alums');
    }

    function getOne($id)
    {
        if ($stmt = $this->con->Prepare("SELECT nom,data_naix FROM autor WHERE id=?")) {
            $rs = $this->con->Execute($stmt, array($id));

            if ($row = $rs->FetchRow()) {
                $result = array(
                    'nom' => $row['nom'],
                    'data_naix' => $row['data_naix']
                );

                return $result;
            }

            return null;
        }
    }

    function showAutors()
    {
        echo "<table border='1'>";
        echo "<tr>";
        echo "<td>id</td><td>nom</td><td>data_naix</td>";
        echo "<td>Elimina</td><td>Edita</td><td>Filtra</td>";
        echo "</tr>";
        $sql = 'select * from autor;';
        $rs = $this->con->Execute($sql);
        if ($rs)
            while ($arr = $rs->FetchRow()) {
                echo "<tr><td>" . $arr['id'] . "</td><td>" . $arr['nom'] . "</td>";
                echo "<td>" . $arr['data_naix'] . "</td><td><a href='http://localhost/conectors?used=" . $this->num . "&dA=" . $arr['id'] . "'>Elimina</a></td>";
                echo "<td><a href='http://localhost/conectors?used=" . $this->num . "&eA=" . $arr['id'] . "'>Editar</a></td><td><a href='http://localhost/conectors?used=" . $this->num . "&autor=" . $arr['id'] . "'>Filtra</a></td></tr>";
            }
        echo "</table>";
    }

    function deleteAutor($id)
    {
        $stmt = $this->con->Prepare("DELETE FROM autor where id=?");
        $this->con->Execute($stmt, array($id));
    }

    function editAutor($data)
    {
        $stmt = $this->con->Prepare("update autor set nom=?, data_naix=? where id=?");
        $this->con->Execute($stmt, array($data['nom'], $data['data_naix'], $data['id']));
    }

    function insertAutor($data)
    {
        $stmt = $this->con->Prepare("insert into autor(nom,data_naix) values(?,?)");
        $this->con->Execute($stmt, array($data['nom'], $data['data_naix']));
    }

    function showLlibres()
    {

        echo "<table border='1'>";
        echo "<tr>";
        echo "<td>id</td><td>id_autor</td><td>titol</td><td>any</td>";
        echo "<td>Elimina</td><td>Edita</td>";
        echo "</tr>";

        $result = $this->con->Execute("select * from llibre");

        if ($result) {
            while ($arr = $result->FetchRow()) {
                echo "<tr><td>" . $arr['id'] . "</td><td>" . $arr['id_autor'] . "</td><td>" . $arr['titol'] . "</td><td>" . $arr['any'] . "</td>";
                echo "<td><a href='http://localhost/conectors?used=" . $this->num . "&dL=" . $arr['id'] . "'>Elimina</a></td><td><a href='http://localhost/conectors?used=" . $this->num . "&eL=" . $arr['id'] . "'>Editar</a></td>";
                echo "</tr>";
            }
        }
        echo "</table>";
    }

    function selectAutor($autor)
    {
        echo "<select name='autors'>";
        $result = $this->con->Execute("select id,nom from autor");
        while ($row = $result->FetchRow()) {
            if ($row['id'] == $autor) {
                echo "<option value='" . $row['id'] . "' selected>" . $row['nom'] . "</option>";
            } else {
                echo "<option value='" . $row['id'] . "'>" . $row['nom'] . "</option>";
            }
        }
        echo "</select>";
    }

    function getLlibre($id)
    {
        $stmt = $this->con->Prepare("SELECT id_autor,titol,any FROM llibre WHERE id=?");
        $result = $this->con->Execute($stmt, array($id));

        if ($row = $result->FetchRow()) {
            $result = array(
                'autor' => $row['id_autor'],
                'titol' => $row['titol'],
                'any' => $row['any']
            );

            return $result;
        }

        return null;
    }

    function showLLibreAutor($id)
    {
        if ($id) {
            $stmt = $this->con->Prepare("SELECT * FROM llibre where id_autor=?");
            $result = $this->con->Execute($stmt, array($id));
            echo "<table border='1'>";
            echo "<tr><td>id</td><td>id_autor</td><td>titol</td><td>any</td><td>Elimina</td><td>Edita</td></tr>";
            while ($row = $result->FetchRow()) {
                echo "<tr><td>" . $row['id'] . "</td><td>" . $row['id_autor'] . "</td><td>" . $row['titol'] . "</td><td>" . $row['any'] . "</td><td><a href='http://localhost/conectors?used=" . $this->num . "&dL=" . $row['id'] . "'>Elimina</a></td><td><a href='http://localhost/conectors?used=" . $this->num . "&eL=" . $row['id'] . "'>Editar</a></td></tr>";
            }
            echo "<tr><td colspan='6' class='centrar'><a href='http://localhost/conectors/?used=" . $this->num . "'>Reset</a></td></tr>";
            echo "</table>";
        }
    }

    function insertLlibre($data)
    {
        $stmt = $this->con->Prepare("insert into llibre(id_autor,titol,any) values(?,?,?)");

        $this->con->Execute($stmt, array($data['id_autor'], $data['titol'], $data['any']));
    }

    function editLlibre($data)
    {
        $stmt = $this->con->Prepare("update llibre set id_autor=?, titol=?, any=? where id=?");
        $this->con->Execute($stmt, array($data['id_autor'], $data['titol'], $data['any'], $data['id']));
    }

    function deleteLlibre($id)
    {
        $stmt = $this->con->Prepare("DELETE FROM llibre where id=?");
        $this->con->Execute($stmt, array($id));
    }
}
