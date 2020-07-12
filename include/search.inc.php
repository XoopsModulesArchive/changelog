<?php

function chan_search($queryarray, $andor, $limit, $offset, $userid)

{
	global $xoopsDB;
	$sql = "SELECT * FROM ".$xoopsDB->prefix('changelog'). " WHERE id>0 ";  // vycteni dat z DB
	
	if ( $userid != 0 ) { $sql .= " AND uid=".$userid." "; }  // Pokud se vypisuje z profilu, tak se vypisuji pouze zaznamy tohoto uzivatele, ne vsech
	
	if ( is_array($queryarray) && $count = count($queryarray) ) {
		$sql .= " AND ((text LIKE '%$queryarray[0]%')";
		for($i=1;$i<$count;$i++){
			$sql .= " $andor ";
			$sql .= "(text LIKE '%$queryarray[$i]%')";
		}
		$sql .= ") ";
	}
	$sql .= "ORDER BY datum DESC";
	$result = $xoopsDB->query($sql,$limit,$offset);
	$ret = array();
	$i = 0;

 	while($myrow = $xoopsDB->fetchArray($result)){
		$ret[$i]['image'] = "images/logo_m.png";                                    // Obrazek pro stranku s vysledky
		$ret[$i]['link'] = "index.php?jeden=".$myrow['id'];                         // Kam prejit po kliknuti na vysledek?
		$ret[$i]['title'] = "Novinky a zmìny";                                      // Text pro stranku s vysledky
		$ret[$i]['time'] = $myrow['datum'];                                         // Datum (unix stamp)
		$ret[$i]['uid'] = $myrow['uid'];                                            // Kdo text vlozil? UID, cili cislo uzivatele
		$i++;
	}
	return $ret;
}
?>
