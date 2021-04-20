<?php
include 'classes/Mysqli.php';
include 'classes/PDO.php';
include 'classes/Adodb.php';
include 'classes/Odbc.php';
$selected = $_GET['used'];
$boto = 'insereix';


if (isset($_POST['send'])) {
    $selected = $_POST['objects'];
    header('Location: http://localhost/conectors?used=' . $selected);
}
$possible_connections = array('Mysqli', 'Adodb', 'Pdo', 'ODBC');

echo "<h3>Selecciona una connexió de BD</h3>";
echo '<form method="post" name="f1">
    <select name="objects">';

for ($i = 0; $i < count($possible_connections); $i++) {
    if ($i == $selected) {
        echo '<option value="' . $i . '" selected>' . $possible_connections[$i] . '</option>';
        continue;
    }
    echo '<option value="' . $i . '">' . $possible_connections[$i] . '</option>';
}

echo '</select><br><br>
    <input type="submit" name="send" value="Selecciona"/>
</form><hr>';

//en cas de que seleccioni metode mostrar la parafernalia

if (isset($_GET['used'])) {
    $nom = '';
    $dNaix = '';

    $selected = $_GET['used'];
    switch ($selected) {
        case 0:
            $obj = new Mysqli2();
            break;
        case 1:
            $obj = new Adodb2();
            break;
        case 2:
            $obj = new PDO2();
            break;
        case 3:
            $obj = new ODBC2();
            break;
        default:
            header("Refresh:0");
    }

    if (isset($_GET['dA'])) {
        $obj->deleteAutor($_GET['dA']);
        header('Location: http://localhost/conectors?used=' . $selected);
    }

    if (isset($_GET['eA'])) {

        $dades = $obj->getOne($_GET['eA']);
        $boto = 'edita';
        if ($dades['nom'] != null && $dades['data_naix'] != null) {
            $nom = $dades['nom'];
            $dNaix = $dades['data_naix'];
        } else {
            header('Location: http://localhost/conectors?used=' . $selected);
        }
    }

    if (isset($_POST['ins'])) {
        if (isset($_GET['eA'])) {
            $data = array(
                'id' => $_GET['eA'],
                'nom' => $_POST['nom'],
                'data_naix' => $_POST['dnaix']
            );

            $obj->editAutor($data);
        } else {
            $data = array(
                'nom' => $_POST['nom'],
                'data_naix' => $_POST['dnaix']
            );
            $obj->insertAutor($data);
        }

        header('Location: http://localhost/conectors?used=' . $selected);
    }


    //dibuixar una taula
    echo "<h2>Taula autors - SGBD utilitzat: " . $possible_connections[$_GET['used']] . " </h2>";
    $obj->showAutors();

    echo '<hr>
    <h2>Inserció / Edició d\'autor</h2>
    <form method="post" action="#" name="f2">
        Nom: <input type="text" value="' . $nom . '" name="nom" /><br>
        Data Naixement: <input type="text" value="' . $dNaix . '" name="dnaix" /><br>
        <input type="submit" value="' . $boto . '" name="ins" />
    </form>    
    ';
}
