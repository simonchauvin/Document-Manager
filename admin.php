<?php

include_once('session.php');
include_once('connect_bdd.php');

echo '<html>';
echo '<head>';
include_once('meta.php');
echo '<script type="text/javascript" src="drop.js"></script>';
echo '<script type="text/javascript" src="utils.js"></script>';
echo '<link rel="stylesheet" type="text/css" href="style.css"/>';
?>
<script>
    function confirm_del()
    {
        if (confirm("Delete the article ?"))
        {
            document.getElementById('deletePubli').value = 1;
            document.forms['FrmSaisiePubli'].submit();
        }
    }
</script>
<?php

echo '</head>';


echo '<body onload="initialisePassiveDrop();">';

include_once('login.php');
include_once('article.php');
include_once('utils.php');


$modif = $_GET['modif'];
if ($modif == '')
    $modif = -1;

$lien = $_GET['lien'];


//Table pour centrage du site
echo '<table border=0 height="100%" width="100%"> <tr><td height="100%" width="100%" align="left" valign="top">';

echo '<b>Resource Manager [administration]</b><hr>';
echo '<a href="./">accueil</a><br>';

$modif_applied = 0;

connect_bdd();

//On met a jour l'article
$article = new Article();
if (isset($_POST['id'])) {
    //On enleve les slashes mis automatiquement sur le post
    foreach ($_POST as $key => $value)
        $_POST[$key] = stripslashes($value);

    if ($_POST['optSujet'] != "")
        $_POST['sujet'] = $_POST['optSujet'];

    $_POST['login'] = $_SESSION['login'];

    $article->setFromAssocFetch($_POST); //renseigne au moins l'id
    if ($article->_id != '') {
        $article->loadFromId(); //récupère ce qui n'est pas posté mais écrase les post
        $article->setFromAssocFetch($_POST); //set les parties postées
    }
}

if ($modif > 0)
    $article->_id = $modif;

if (isset($_POST['deletePubli']) && $_POST['deletePubli'] == 1) {
    $article->deleteFromDb();
    $article = new Article();
}

if (isset($_POST['addPubli'])) {
    $article->insertAndGetIndex();
    echo "<b>Publi ajoutee</b><br>";
    $modif_applied = 1;
}

if (isset($_POST['modifPubli'])) {
    $article->update();
    echo '<b>Publi modifiée à ' . date('H:i') . '</b><br>';
    $modif_applied = 1;
}

//On rerécupère l'article
if ($article->_id > 0)
    $article->loadFromId();

//$makeLienEditable = false;
if ($lien != '' && $article->_lien == '') {
    $article->_lien = $lien;
    echo '<script>window.addEventListener("load", function(){ makeChampAdminFormEditable("c_lien");}, true);</script>';
    ///$makeLienEditable = true;		
}

/* if($article->_lien != '')
  {
  $fontSizes = array();
  $clearTexts = array();
  pdf2text($article->_lien,$fontSizes,$clearTexts);
  $article->_plainPdf = implode($clearTexts);
  //echo 'pdf: '.$clearTexts[0].$article->_plainPdf;
  if($fillFromPdf != -1){
  $title = getTitleFromTextArray($fontSizes,$clearTexts);
  $article->_titre = $title;
  }
  } */

//récuperation des sujets existants
$querySujet = 'SELECT DISTINCT(sujet) FROM resource WHERE login=\'' . $_SESSION['login'] . '\' ORDER BY sujet ASC';
$resultSujet = mysql_query($querySujet) or die('Query failed : ' . $querySujet);
$optionSujet = '<select class="btn" id="optSujet" name="optSujet" style="width:48%">';
$selected = ($article->_sujet == "") ? "selected" : "";
$optionSujet .= '<option value="" ' . $selected . '>';
while ($row = mysql_fetch_assoc($resultSujet)) {
    $selected = ($article->_sujet == $row["sujet"]) ? "selected" : "";
    if ($row["sujet"] != "") {
        $optionSujet .= '<option value="' . $row["sujet"] . '" ' . $selected . '>' . $row["sujet"];
    }
}
mysql_free_result($resultSujet);
$optionSujet .= '</select>';

$action = '';
//if($article->_id > 0)
//$action = 'action="admin.php?modif='.$article->_id.'"';

echo '<form action="' . $action . '" type="multipart/form-data" name="FrmSaisiePubli" method="POST">';
echo '<table border=0>';
echo '<tr><td valign="top"><table border=0>';
echo '<tr><td align="right" valign="top">tools: </td><td>
			<a href="javascript:cleanChamps()"/>Clean</a> ::
			 </td></tr>';
echo '<input type="hidden" name="id" value="' . $article->_id . '">';
echo '<tr><td align="right" valign="top">sujet: </td><td id="c_sujet" >' . $optionSujet . '&nbsp;&nbsp;<input class="btn" type="text" name="sujet" value="" style="width:50%"></td></tr>';
echo '<tr><td align="right" valign="top">auteur: </td><td width="700px" id="c_auteur" onclick="makeChampAdminFormEditable(\'c_auteur\')">' . $article->_auteur . '</td></tr>';
echo '<tr><td align="right" valign="top">date: </td><td id="c_date" onclick="makeChampAdminFormEditable(\'c_date\')">' . $article->_date . '</td></tr>';
echo '<tr><td align="right" valign="top">titre: </td><td id="c_titre" onclick="makeChampAdminFormEditable(\'c_titre\')">' . $article->_titre . '</td></tr>';
echo '<tr><td align="right" valign="top">résumé: </td><td id="c_resume" onclick="makeChampAdminFormEditable(\'c_resume\')">' . nl2br($article->_resume) . '</td></tr>';
echo '<tr><td align="right" valign="top">lien: </td><td id="c_lien" onclick="makeChampAdminFormEditable(\'c_lien\')">' . $article->_lien . '</td></tr>';


echo '<tr><td align="right" valign="top">lu: </td><td><input class="btn" type="checkbox" name="lu" ' . (($article->_lu) ? 'checked' : '') . ' >';
echo 'sauv mail: <input class="btn" type="checkbox" name="saveByMail" 0 ></td></tr>';
echo '<tr><td colspan=3 height="20"></td></tr>';

if ($article->_id > 0) {
    echo '<tr><td colspan=2><input class="btn" type="submit" name="modifPubli" value="modifier la publi"></td></tr>';
    echo '<tr><td height="50px" colspan=2> </td></tr>';
    echo '<tr><td colspan=2><input type="hidden" id="deletePubli" name="deletePubli" value=""><input class="redbtn" type="button" name="btnDelete" value="supprimer la publi" onclick="confirm_del()"></td></tr>';
} else
    echo '<tr><td colspan=2><input class="btn" type="submit" name="addPubli" value="ajouter la publi"></td></tr>';

echo '</form>';

echo '</table></td><td valign=top>';
echo '</td></tr>';
echo '</table>';

//Si on a fait des modifs
if ($modif_applied) {
    //Sauvegarde mail ?
    $last_save = load_data('DateLastMail');
    echo '(Dernière sauvegarde le ' . date('j M Y', $last_save) . ' à ' . date('G:i:s)', $last_save) . '<br>';
    $last_save = (mktime() - $last_save) / 3600; //en heures

    if ($modif_applied && ($last_save > 2 || $_POST['saveByMail'])) {
        echo '<b>Sauvegarde par mail effectuée à ' . date('H:i:s') . ' pour ' . $g_mails[$_SESSION['login']] . '</b><br>';

        //On sauve dans le fichier
        save_data('DateLastMail', mktime());

        $content = '';

        $query = 'show create table resource';
        $result = mysql_query($query) or die("Query failed");
        $line = mysql_fetch_row($result);

        $content .= $line[1] . ';';

        $content .= "\r\n";
        $content .= "\r\n";

        $query = 'select * from resource where login=\'' . $_SESSION['login'] . '\'';
        $result = mysql_query($query) or die("Query failed");

        while ($line = mysql_fetch_assoc($result)) {
            $content .= 'insert into resource values(';
            $first = 1;
            foreach ($line as $key => $value) {
                $value = utf8_encode($value);
                $value = str_replace('\'', '\\\'', $value);

                if (!$first)
                    $content .= ',';
                $first = 0;
                $content .= '\'' . $value . '\'';
            }
            $content .= ');';
            $content .= "\r\n";
        }

        $content .= "\r\n";
        $content .= "\r\n";
    }

    mysql_close();
}

echo '</table>';

echo '</body>';
echo '</html>';
?>