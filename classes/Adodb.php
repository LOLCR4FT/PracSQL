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
        echo "<td>Elimina</td><td>Edita</td>";
        echo "</tr>";
        $sql = 'select * from autor;';
        $rs = $this->con->Execute($sql);
        if ($rs)
            while ($arr = $rs->FetchRow()) {
                echo "<tr><td>" . $arr['id'] . "</td><td>" . $arr['nom'] . "</td>";
                echo "<td>" . $arr['data_naix'] . "</td><td><a href='http://localhost/conectors?used=" . $this->num . "&dA=" . $arr['id'] . "'>Elimina</a></td>";
                echo "<td><a href='http://localhost/conectors?used=" . $this->num . "&eA=" . $arr['id'] . "'>Editar</a></td></tr>";
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
}
