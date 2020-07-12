<?php

// -----------------[ Hlavicka souboru, vlozeni systemovych prostredku ]--------------------------------------------------


include '../../../include/cp_header.php';


if ( file_exists("../language/".$xoopsConfig['language']."/admin.php") )				// Kontrola existence jazykovych souboru
{
	include_once ("../language/".$xoopsConfig['language']."/admin.php");          // Pokud existuje, pouzije se
	include_once("../language/".$xoopsConfig['language']."/modinfo.php");
}
else
{
	include_once("../language/czech/admin.php");																	// Pokud neexistuje, pouziju svuj, tady cestinu
	include_once("../language/czech/modinfo.php");
}

// -----------------[ Vychozi stranka BEZ predaneho parametru "CO" ]------------------------------------------------------

if (!isset($_GET["co"]))
{
 global $xoopsModule;
 xoops_cp_header(); 																														// Hlavicka stranky administrace. Opakuje se pro kazde zobrazeni

   if (isset($_POST['form_co']))    																						// Provedeni zapisu do databaze
   {
			if ($_POST["form_co"]=="save")
			{
    	 	 $sql = "INSERT INTO " . $xoopsDB -> prefix('changelog') . " SET text=\"".$_POST['form_text']."\", datum=\"".time()."\" , uid=\"".$xoopsUser->getVar('uid')."\" ";
				 $result = $xoopsDB -> query($sql);
     	}

			if ($_POST["form_co"]=="change")
			{
    	 	 $sql = "UPDATE " . $xoopsDB -> prefix('changelog') . " SET text=\"".$_POST['form_text']."\" WHERE id=".$_POST['form_id']." ";
				 $result = $xoopsDB -> query($sql);
     	}

			if ($_POST["form_co"]=="delete")
			{
    	 	 $sql = "DELETE FROM " . $xoopsDB -> prefix('changelog') . " WHERE id=".$_POST['form_id']." ";
				 $result = $xoopsDB -> query($sql);
     	}

   }

   echo "<h2 align = 'center'>" . _CHAN_NAZEV_MODULU . " - "._CHAN_ADMIN_NADPIS . "</h2>";

   echo "<center><b>";                                  // Vypsani hlasky o provedene aktualizaci tabulek
   if ( isset($result) && ($result==1)) echo _CHAN_ADMIN_AKTUAL_OK;
   if ( isset($result) && ($result==0)) echo _CHAN_ADMIN_AKTUAL_KO;
   echo "</b></center><br>";                         // Pokud nebyla aktualizace, tak se nezobrazuje
   
   echo "<p>"._CHAN_ADMIN_MOZNOSTI."</p>";
   echo "<br><br><ul>";
   echo "<li><a href='index.php?co=zapis'>"._CHAN_ADMIN_ZAPIS  ."</a> " . _CHAN_ADMIN_ZAPIS_P. "</li>";
   echo "<li><a href='index.php?co=uprav'>"._CHAN_ADMIN_UPRAV  ."</a> " . _CHAN_ADMIN_UPRAV_P. "</li>";
   echo "<li><a href='../index.php'>"._CHAN_ADMIN_PREJIT.     "</a> " . _CHAN_ADMIN_PREJIT_P. "</li>";
   echo "</ul>";
   echo "<br><br>";

   echo "<hr><p align='right'>"._CHAN_ADMIN_PATICKA."</p>";
   
   xoops_cp_footer();   																												// Paticka stranky administrace. Opakuje se pro kazde zobrazeni
}
// -----------------------------------------------------------------------------------------------------------------------

// -----------------[ Novy zaznam do databaze / New item ]----------------------------------------------------------------
if (isset($_GET["co"]) && ($_GET["co"]=="zapis" ))
{
   global $xoopsModule;
   xoops_cp_header();

   echo "<h3 align='center'> " . _CHAN_NAZEV_MODULU . "</h3> <center><b>"._CHAN_ADMIN_PODNADPIS_NOVY ."</b><br><br>";


   echo "<form action='".$_SERVER['PHP_SELF']."' method='post'>";               // Zobrazeni formulare

   echo "<textarea name='form_text' rows=10 cols=40>"._CHAN_ADMIN_NOVY_TEXT."</textarea><br><br>";
   echo "<input type='hidden' name='form_co' value='save'>";
   echo "<input type='reset' value='"._CHAN_ADMIN_PUVODNI."'> <input type='submit' value='"._CHAN_ADMIN_ULOZIT."'>";
   echo "</form>";
   echo "</center><br><br>";
   echo "<a href='index.php'>"._CHAN_ADMIN_ZPET."</a>";                         // Tlacitko "ZPET"

   xoops_cp_footer();
}
// -----------------------------------------------------------------------------------------------------------------------

// -----------------[ Vypsani zaznamu a jejich pripadna zmena / List of items ]-------------------------------------------
if (isset($_GET["co"]) && ($_GET["co"]=="uprav" ))
{
   global $xoopsModule;
   xoops_cp_header();

	$krok = $xoopsModuleConfig['krok_admin'];

  echo "<h3 align='center'>" . _CHAN_NAZEV_MODULU . "</h3> <center><b>"._CHAN_ADMIN_PODNADPIS_VYPIS ."</b></center><br><br>";

	if (isset($_GET["limit"])) { $limit = $_GET["limit"]; }
	else { $limit=0; }

	$sql = "SELECT * FROM ".$xoopsDB -> prefix('changelog');      // Zjisteni celkoveho poctu zaznamu
  $result = $xoopsDB -> query($sql);
	$celkem = mysql_num_rows($result);	                  																									// Krok vypisu / step of list

	$sql = "SELECT * FROM ".$xoopsDB -> prefix('changelog')." ORDER BY id DESC LIMIT ".$limit." ,".$krok." "; // Postupne vypisovani
  $result = $xoopsDB -> query($sql);

	echo "<table border='1' rules='all'>";
	
	echo "<tr><th>ID</th><th>Datum zápisu</th><th>Text</th><th>Akce</th></tr>";

	if (mysql_num_rows($result)> 0)
	{

		while ($myrow=mysql_fetch_array($result))
		{
			echo "<tr valign='top' ><td align='center'><b>".$myrow['id']."</b></td><td><b>".date("j.n.Y",$myrow['datum'])." ".date("H:i",$myrow['datum'])."</b></td><td>" . nl2br($myrow['text'])."</td> <td align='center'> <a href='".$_SERVER['PHP_SELF']."?co=nastav&id=".$myrow['id']."'>"._CHAN_ADMIN_ZMENA."</a> <a href='".$_SERVER['PHP_SELF']."?co=smazat&id=".$myrow['id']."'> "._CHAN_ADMIN_SMAZAT." </a></td></tr>";
		}

	}
	else          // Prazdna databaze / empty database
	{
	 	 echo "<tr valign='top' ><td align='center' colspan='4'><b>"._CHAN_ADMIN_ZADNY_ZAZNAM."</b></td></tr>";
	}

	 echo "</table>";
	 
			if ((mysql_num_rows($result) == $krok)  &&  (($limit + $krok) != $celkem) ) // Dokud nejsem na konci, zobrazuji sipku DALSI
			{
				 	 	$prava = "<a href='index.php?co=uprav&limit=".($limit+$krok)."'  > >>>>> </a>";
			}

			if ( $limit > 0 )                     																		// Dokud nejsem na zacatku, zobrazuji sipku NOVEJSI
			{
				 	 	$leva = "<a href='index.php?co=uprav&limit=".($limit-$krok)."'  > <<<<<< </a>";
			}

		if ( ($limit+$krok) > $celkem )
		{
			$ciselnik = ($limit+1)." - ". $celkem. " / " .$celkem;
		}
		else
		{
			$ciselnik = ($limit+1)." - ". ($limit+$krok). " / " .$celkem;
		}

	echo "<br><table border=0><tr align='center'><td width=20%>". $leva."</td><td width=60%><b>".$ciselnik."</b></td><td width=20%>".$prava."</td></tr></table>";

   echo "<br><br><br><a href='index.php'>"._CHAN_ADMIN_ZPET."</a>";                         // Tlacitko "ZPET"

   xoops_cp_footer();
}
// -----------------------------------------------------------------------------------------------------------------------

// -----------------[ Zmena zaznamu / Change item ]-----------------------------------------------------------------------
if (isset($_GET["co"]) && ($_GET["co"]=="nastav" ))
{
   global $xoopsModule;
   xoops_cp_header();

   echo "<h3 align='center'>" . _CHAN_NAZEV_MODULU . "</h3> <center><b>"._CHAN_ADMIN_PODNADPIS_ZMENA ."</b><br><br>";


   echo "<form action='".$_SERVER['PHP_SELF']."' method='post'>";               // Zobrazeni formulare

   $sql = "SELECT * FROM " . $xoopsDB -> prefix('changelog') . " WHERE id=".$_GET["id"]." ";       			// Z DB vyctu nadpis
   $result = $xoopsDB -> query($sql);
   $myrow = $xoopsDB->fetchArray($result);
   echo "<textarea name='form_text' rows=10 cols=40>".$myrow['text']."</textarea><br><br>";
   echo "<input type='hidden' name='form_co' value='change'>";
   echo "<input type='hidden' name='form_id' value='".$_GET["id"]."'>";
   echo "<input type='reset' value='"._CHAN_ADMIN_PUVODNI."'> <input type='submit' value='"._CHAN_ADMIN_ULOZIT."'>";
   echo "</form>";
	 echo "<p>".date("j. n. Y H:i",$myrow['datum'])."</p>";
   echo "</center><br><br>";
   echo "<a href='index.php'>"._CHAN_ADMIN_ZPET."</a>";                         // Tlacitko "ZPET"

   xoops_cp_footer();
}
// -----------------------------------------------------------------------------------------------------------------------

// -----------------[ Smazani zaznamu / Delete item ]-----------------------------------------------------------------------
if (isset($_GET["co"]) && ($_GET["co"]=="smazat" ))
{
   global $xoopsModule;
   xoops_cp_header();
   
   $sql = "SELECT * FROM " . $xoopsDB -> prefix('changelog') . " WHERE id=".$_GET["id"]." ";       			// Z DB vyctu nadpis
   $result = $xoopsDB -> query($sql);
   $myrow = $xoopsDB->fetchArray($result);

   echo "<h3 align='center'>" . _CHAN_NAZEV_MODULU . "</h3> <center><b>"._CHAN_ADMIN_PODNADPIS_ZMENA ."</b><br><br>";

	 echo "<p><b><font color='#FF0000'>"._CHAN_ADMIN_POTVRZENI." ".date("j. n. Y H:i",$myrow['datum'])."?</font></b></p>";


	echo "<table width=100%><tr><td  width=50% align='right'>";

   echo "<form action='".$_SERVER['PHP_SELF']."' method='post'>";               // Zobrazeni formulare s ANO / Yes
   echo "<input type='hidden' name='form_co' value='delete'>";
   echo "<input type='hidden' name='form_id' value='".$_GET["id"]."'>";
   echo "<input type='submit' value='"._CHAN_ADMIN_ANO."'>";
   echo "</form>";
   
   echo "</td><td  width=50% align='left'>";
   
   echo "<form action='".$_SERVER['PHP_SELF']."' method='post'>";               // Zobrazeni formulare s NE /None
   echo "<input type='submit' value='"._CHAN_ADMIN_NE."'>";
   echo "</form>";
   
   echo "</td></tr></table>";

   echo "</center><br><br>";
   echo "<a href='index.php'>"._CHAN_ADMIN_ZPET."</a>";                         // Tlacitko "ZPET"

   xoops_cp_footer();
}
// -----------------------------------------------------------------------------------------------------------------------



?>