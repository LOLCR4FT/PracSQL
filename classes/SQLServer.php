<?php
class sqlsrv
{
    private $con;
    private $num = 3;
    function __construct()
    {
        $serverName = "DESKTOP-02SDIP8\SQLEXPRESS"; //serverName\instanceName

        // Since UID and PWD are not specified in the $connectionInfo array,
        // The connection will be attempted using Windows Authentication.
        $connectionInfo = array( "Database"=>"alums");
        $this->con = sqlsrv_connect( $serverName, $connectionInfo);
        
        if( !$this->con) {
             echo "Connection could not be established.<br />";
             die( print_r( sqlsrv_errors(), true));
        }
    }

    function getOne($id)
    {
        if ($id) {
            $sql = "SELECT nom,data_naix FROM autor WHERE id=?";
            $stmt = sqlsrv_query($this->con,$sql,array($id));

            if($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                sqlsrv_free_stmt($stmt);
                return $row;
            }

            return null;
        }
    }

    function showAutors()
    {
        $sql = "SELECT * FROM autor";
        $stmt = sqlsrv_query( $this->con, $sql );
        echo "<table border='1'>";
        echo "<tr>";
        echo "<td>id</td><td>nom</td><td>data_naix</td>";
        echo "<td>Elimina</td><td>Edita</td><td>Filtra</td>";
        echo "</tr>";

        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td><td>" . $row['nom'] . "</td><td>" . $row['data_naix'] . "</td>";
            echo "<td><a href='http://localhost/conectors?used=" . $this->num . "&dA=" . $row['id'] . "'>Elimina</a></td><td><a href='http://localhost/conectors?used=" . $this->num . "&eA=" . $row['id'] . "'>Editar</a></td>
            <td><a href='http://localhost/conectors?used=" . $this->num . "&autor=" . $row['id'] . "'>Filtra</a></td>            
            ";
            echo "</tr>";
        }
        echo "</table>";
    }

    function deleteAutor($id)
    {
        if ($id) {
            $sql = "DELETE FROM autor WHERE id=?";
            $stmt = sqlsrv_prepare($this->con,$sql,array($id));
            sqlsrv_execute($stmt);
        }
    }

    function editAutor($data)
    {
        $sql = "update autor set nom=?, data_naix=? where id=?";
        $stmt = sqlsrv_prepare($this->con,$sql,array($data['nom'],$data['data_naix'],$data['id']));
        sqlsrv_execute($stmt);
    }

    function insertAutor($data)
    {
        $sql = "insert into autor(nom,data_naix) values(?,?)";
        $stmt = sqlsrv_prepare($this->con,$sql,array($data['nom'],$data['data_naix']));
        sqlsrv_execute($stmt);
    }
    function showLlibres()
    {
        echo "<table border='1'>";
        echo "<tr>";
        echo "<td>id</td><td>id_autor</td><td>titol</td><td>any</td>";
        echo "<td>Elimina</td><td>Edita</td>";
        echo "</tr>";


        $sql = "SELECT * FROM llibre";
        $stmt = sqlsrv_query( $this->con, $sql );

        while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['id_autor'] . "</td>";
            echo "<td>" . $row['titol'] . "</td>";
            echo "<td>" . $row['anyy'] . "</td>";
            echo "<td><a href='http://localhost/conectors?used=" . $this->num . "&dL=" . $row['id'] . "'>Elimina</a></td><td><a href='http://localhost/conectors?used=" . $this->num . "&eL=" . $row['id'] . "'>Editar</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    function selectAutor($autor)
    {
        echo "<select name='autors'>";
        $sql = "SELECT * FROM autor";
        $stmt = sqlsrv_query( $this->con, $sql,array($autor));
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            sqlsrv_free_stmt($stmt);
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
        if ($id) {

            $sql = "SELECT id_autor as autor,titol,anyy as 'any' FROM llibre WHERE id=?";
            $stmt = sqlsrv_query($this->con,$sql,array($id));

            if($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                sqlsrv_free_stmt($stmt);
                return $row;
            }
        }
        return null;
    }

    function showLLibreAutor($id)
    {
        if ($id) {
            $sql = "SELECT * FROM llibre WHERE id_autor=?";
            $stmt = sqlsrv_query($this->con,$sql,array($id));
            echo "<table border='1'>";
            echo "<tr><td>id</td><td>id_autor</td><td>titol</td><td>any</td><td>Elimina</td><td>Edita</td></tr>";
            while ($result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                echo "<tr><td>" . $result['id'] . "</td><td>" . $result['id_autor'] . "</td><td>" . $result['titol'] . "</td><td>" . $result['anyy'] . "</td><td><a href='http://localhost/conectors?used=" . $this->num . "&dL=" . $result['id'] . "'>Elimina</a></td><td><a href='http://localhost/conectors?used=" . $this->num . "&eL=" . $result['id'] . "'>Editar</a></td></tr>";
            }
            echo "<tr><td colspan='6' class='centrar'><a href='http://localhost/conectors/?used=" . $this->num . "'>Reset</a></td></tr>";
            echo "</table>";
        }
    }

    function insertLlibre($data)
    {
        $sql = "insert into llibre(id_autor,titol,anyy) values(?,?,?)";
        $stmt = sqlsrv_prepare($this->con,$sql,array($data['id_autor'],$data['titol'],$data['any']));
        sqlsrv_execute($stmt);
    }

    function editLlibre($data)
    {
        $sql = "update llibre set id_autor=?, titol=?, anyy=? where id=?";
        $stmt = sqlsrv_prepare($this->con,$sql,array($data['id_autor'],$data['titol'],$data['any'],$data['id']));
        sqlsrv_execute($stmt);    
    }

    function deleteLlibre($id)
    {
        $sql = "DELETE FROM llibre where id=?";
        sqlsrv_query($this->con,$sql,array($id));
    }

    function hasBooks($id)
    {
        if ($id) {

            $sql = "SELECT id FROM llibre WHERE id_autor=?";
            $stmt = sqlsrv_prepare($this->con,$sql,array($id));
            sqlsrv_execute($stmt);
            if($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                return true;
            }
        }
        return false;
    }
}
