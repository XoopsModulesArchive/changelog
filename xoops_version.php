<?php
$modversion['name']        = _CHAN_NAZEV_MODULU;					              				// Nazev modulu, vlozi se z jaykoveho souboru. Pro zobrazeni ve spravci modulu
$modversion['version'] 	   = 1.12;             						               				// Cislo verze, zobrazuje se ve spravci
$modversion['description'] = _CHAN_POPIS_MODULU; 							           				// Popis modulu pro Credits a podobne
$modversion['author']	   = "Sasa Svobodova";                 										// autor modulu, opet pro Credits
$modversion['credits'] 	   = "www.zirafoviny.cz";																//   -- // --
$modversion['help'] 	   = "no";                               								// Obsahuje HELP?
$modversion['license'] 	   = "GNU GPL";		               												// Licence modulu, vlasne vzdy GPL
$modversion['official']    = "no";                           										// Oficialni modul?
$modversion['image']       = "images/logo.png";                 								// Cesta k obrazku loga modulu
$modversion['dirname']     = "changelog";  	                  								// Nazev adresare modulu

// Menu - "Uzivatelska nabidka"
$modversion['hasMain'] 	      = 1;                             									// Zobrazit v menu?

// Administrace
$modversion['hasAdmin']	  = 1;                      														// Obsahuje administraci?
$modversion['adminindex'] = "admin/index.php";                                  // cesta k INDEXu administrace
$modversion['adminmenu']  = "admin/menu.php";                                   // cesta k menu, dava se samostatne

// Soubor SQL - nastaveni databaze
$modversion['sqlfile']['mysql']		= "sql/mysql.sql";														// Nazvy tabulek museji byt BEZ prefixu!
$modversion['tables'][0]	= "changelog"; 																					// Tabulky ktere vytvori SQL soubor, opet bez prefixu. Je to dùležité hlavnì pro odinstalaci modulu a jeho upravy

// Templates - sablonky
$modversion['templates'][1]['file']        = 'chan_index.html';  								// Jmeno sablony
$modversion['templates'][1]['description'] = 'Template for Changelog.';		  // Popis sablony pro spravce sablon

// Hledani XOOPS
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/search.inc.php";
$modversion['search']['func'] = "chan_search";

// Nastaveni
$modversion['config'][1]['name'] = 'krok_admin';
$modversion['config'][1]['title'] = '_CHAN_ADMIN_KROK';
$modversion['config'][1]['description'] = '_CHAN_ADMIN_KROK_P';
$modversion['config'][1]['formtype'] = 'textbox';
$modversion['config'][1]['valuetype'] = 'int';
$modversion['config'][1]['default'] = 10;

$modversion['config'][2]['name'] = 'krok_lide';
$modversion['config'][2]['title'] = '_CHAN_KROK';
$modversion['config'][2]['description'] = '_CHAN_KROK_P';
$modversion['config'][2]['formtype'] = 'textbox';
$modversion['config'][2]['valuetype'] = 'int';
$modversion['config'][2]['default'] = 10;

$modversion['config'][3]['name'] = 'text_nadpis';
$modversion['config'][3]['title'] = '_CHAN_NADPIS';
$modversion['config'][3]['description'] = '_CHAN_NADPIS_P';
$modversion['config'][3]['formtype'] = 'textbox';
$modversion['config'][3]['valuetype'] = 'text';
$modversion['config'][3]['default'] = _CHAN_NADPIS_D;

$modversion['config'][4]['name'] = 'text_intro';
$modversion['config'][4]['title'] = '_CHAN_INTRO';
$modversion['config'][4]['description'] = '_CHAN_INTRO_P';
$modversion['config'][4]['formtype'] = 'textarea';
$modversion['config'][4]['valuetype'] = 'text';
$modversion['config'][4]['default'] = _CHAN_INTRO_D;

?>