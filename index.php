<?php
// -----------------[ Hlavicka souboru, vlozeni systemovych prostredku ]--------------------------------------------------

require_once("../../mainfile.php");																							// vlozeni systemovych promenych
global $xoopsTpl;
$xoopsOption['template_main'] = 'chan_index.html'; 	     												// vlozeni hlavni sablony, toto MUSI byt vlozeno pred HEADERem!!!!
include(XOOPS_ROOT_PATH."/header.php");               													// vlozeni hlavicky stránky

$count=1;

// -----------------[ Vlastni vykonny kod modulu ] -----------------------------------------------------------------------

$xoopsTpl->assign('chan_nadpis', $xoopsModuleConfig['text_nadpis'] );
$xoopsTpl->assign('chan_intro', $xoopsModuleConfig['text_intro'] );
$xoopsTpl->assign('chan_co', _CHAN_CO );
$xoopsTpl->assign('chan_kdy', _CHAN_KDY );
$xoopsTpl->assign('chan_kdo', _CHAN_KDO );

if (isset($_GET["limit"])) { $limit = $_GET["limit"]; }
else { $limit=0; }
	 
$krok = $xoopsModuleConfig['krok_lide'];                																									// Krok vypisu / step of list

if (isset($_GET["jeden"]))  // Jestlize je pozadavek na jeden vypis, tak zkontoluji, jestli v DB vubec je
{
	$sql = "SELECT * FROM ".$xoopsDB -> prefix('changelog')." WHERE id='".$_GET['jeden']."' ";
}
else  // neni pozadavek na jeden vypis, tak zkontroluji, jestli v DB neco je
{
	$sql = "SELECT * FROM ".$xoopsDB -> prefix('changelog');
}
$result = $xoopsDB -> query($sql);

$celkem = mysql_num_rows($result);

$sql = "SELECT * FROM ".$xoopsDB -> prefix('changelog')." ORDER BY id DESC LIMIT ".$limit.",".$krok." ";
$result = $xoopsDB -> query($sql);

if ($celkem > 0)     // V DB jsou zaznamy, proto budu vypisovat
{

		if (isset($_GET["jeden"]))  // Vypsani pouze jednoho zaznamu
		{
					$sql = "SELECT * FROM ".$xoopsDB -> prefix('changelog')." WHERE id='".$_GET['jeden']."' ";
					$result = $xoopsDB -> query($sql);
					$myrow=mysql_fetch_array($result);

		 			$hodina = date("H",$myrow['datum']);      // vypsani casu zaznamu
					if ( ($hodina == 2) || ($hodina == 3) || ($hodina == 4) || ($hodina == 12) || ($hodina == 13) || ($hodina == 14) || ($hodina == 20) ||($hodina == 21) ||($hodina == 22) || ($hodina == 23) ) 	{ $predlozka=_CHAN_VE; }
					else { $predlozka=_CHAN_V; }
					
					$sql = "SELECT * FROM ".$xoopsDB -> prefix('users')." WHERE uid='".$myrow['uid']."' ";
		  		$result1 = $xoopsDB -> query($sql);
					$myrow1=mysql_fetch_array($result1);

					$xoopsTpl->append('vypis', array('id' => $myrow['id'],'datum' => date("j.n.Y",$myrow['datum']), 'cas' => $predlozka ." ".date("H:i",$myrow['datum']), 'text' => nl2br($myrow['text']), 'uid' => $myrow['uid'], 'uname' => $myrow1['uname'], 'count' => "1"));
		}

		else      // Vypsani celeho zaznamu, provede se i strankovani
		{

			if ((mysql_num_rows($result) == $krok)  &&  (($limit + $krok) != $celkem) ) // Zobrazeni navigace novejsi / starsi, Dokud nejsem na konci, zobrazuji sipku DALSI
			{
				 	 	$xoopsTpl->assign('chan_dalsi', "<a href='index.php?limit=".($limit+$krok)."'  > >>>>> </a>" );
			}

			if ( $limit > 0 )                     																		// Dokud nejsem na zacatku, zobrazuji sipku NOVEJSI
			{
				 	 	$xoopsTpl->assign('chan_novejsi', "<a href='index.php?limit=".($limit-$krok)."'  > <<<<<< </a>" );
			}

		if ( ($limit+$krok) > $celkem )
		{
			$ciselnik = ($limit+1)." - ". $celkem. " / " .$celkem;
		}
		else
		{
			$ciselnik = ($limit+1)." - ". ($limit+$krok). " / " .$celkem;
		}

		$xoopsTpl->assign('chan_ciselnik', $ciselnik );


			while ($myrow=mysql_fetch_array($result))
			{

			 			$hodina = date("H",$myrow['datum']);      // vypsani casu zaznamu
						if ( ($hodina == 2) || ($hodina == 3) || ($hodina == 4) || ($hodina == 12) || ($hodina == 13) || ($hodina == 14) || ($hodina == 20) ||($hodina == 21) ||($hodina == 22) || ($hodina == 23) )
						{
			  		 	 $predlozka=_CHAN_VE;
					  }
						else
						{
		   		 	 		$predlozka=_CHAN_V;
						}     //

				// Zjisteni uzivatelskeho jmena podle cisla uzivatele
				// UID - je v tabulce CHANGELOG
				// UNAME - je v tabulce USERS

				$sql = "SELECT * FROM ".$xoopsDB -> prefix('users')." WHERE uid='".$myrow['uid']."' ";
	  		$result1 = $xoopsDB -> query($sql);
				$myrow1=mysql_fetch_array($result1);
				// Predani hodnot sablone TEMPLATES
				$xoopsTpl->append('vypis', array('id' => $myrow['id'],'datum' => date("j.n.Y",$myrow['datum']), 'cas' => $predlozka ." ".date("H:i",$myrow['datum']), 'text' => nl2br($myrow['text']), 'uid' => $myrow['uid'], 'uname' => $myrow1['uname'], 'count' => $count));
				$count++;
			}

		}

}

else          // Prazdna databaze / empty database
{
			$sql = "SELECT * FROM ".$xoopsDB -> prefix('users')." WHERE uid='1' ";
  		$result1 = $xoopsDB -> query($sql);
			$myrow1=mysql_fetch_array($result1);

			$xoopsTpl->append('vypis', array('datum' => date("j. n. Y",time()), 'text' => "<center><b>"._CHAN_PRAZDNA_DB."</b></center>", 'uid' => "1", 'uname' => $myrow1['uname'], 'count' => $count));
			$count++;
}
	
if ( is_object($xoopsUser) && $xoopsUser->isAdmin())                            // Pokud jsem ADMIN, tak zobrazim odkaz
{
 	 echo "<hr><p align='right'><a href='./admin/index.php'>"._CHAN_ADMINISTRACE."</p>";
}

// ----------------- [ Vlozeni paticky stranky ] ----------------------------------------------------------------------------
include(XOOPS_ROOT_PATH."/footer.php");
?>