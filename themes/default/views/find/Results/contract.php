<?php
/* ----------------------------------------------------------------------
 * themes/default/views/find/Results/docx_results.php
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2013-2016 Whirl-i-Gig
 *
 * For more information visit http://www.CollectiveAccess.org
 *
 * This program is free software; you may redistribute it and/or modify it under
 * the terms of the provided license as published by Whirl-i-Gig
 *
 * CollectiveAccess is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTIES whatsoever, including any implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 *
 * This source code is free and modifiable under the terms of 
 * GNU General Public License. (http://www.gnu.org/copyleft/gpl.html). See
 * the "license.txt" file for details, or visit the CollectiveAccess web site at
 * http://www.CollectiveAccess.org
 *
 * ----------------------------------------------------------------------
 */
 
	$t_display			= $this->getVar('t_display');
	$va_display_list 		= $this->getVar('display_list');
	$vo_result 			= $this->getVar('result');
	$vn_items_per_page 		= $this->getVar('current_items_per_page');
	$vs_current_sort 		= $this->getVar('current_sort');

###################################
//de data
while($vo_result->nextHit()) {
	$list = $va_display_list;

	foreach($list as $vn_placement_id => $va_info) {
		$vs_display_text = $t_display->getDisplayValue($vo_result, $vn_placement_id, array_merge(array('request' => $this->request, 'purify' => true), is_array($va_info['settings']) ? $va_info['settings'] : array()));
		$field = $va_info['display'];
		switch($field) {
			case 'nemer':
				$nemer = caEscapeForXML(html_entity_decode(strip_tags(br2nl($vs_display_text))));
			case 'straat':
				$straat = caEscapeForXML(html_entity_decode(strip_tags(br2nl($vs_display_text))));
			case 'plaats':
				$plaats = caEscapeForXML(html_entity_decode(strip_tags(br2nl($vs_display_text))));
			case 'attentie':
				$attentie = caEscapeForXML(html_entity_decode(strip_tags(br2nl($vs_display_text))));
			default:
				break;
		}
	}
}
#####################################################

	// For easier calculation
	// 1 cm = 1440/2.54 = 566.93 twips
	$cmToTwips = 567;

	$phpWord = new \PhpOffice\PhpWord\PhpWord();

	// Every element you want to append to the word document is placed in a section.

    	// New portrait section
	$sectionStyle = array(
	    'orientation' => 'portrait',
	    'marginTop' => 1 * $cmToTwips,
	    'marginBottom' => 1 * $cmToTwips,
	    'marginLeft' => 1 * $cmToTwips,
	    'marginRight' => 2 * $cmToTwips,
	    'headerHeight' => 1 * $cmToTwips,
	    'footerHeight' => 1 * $cmToTwips,
	    'colsNum' => 1,
	);

	// Defining font style for headers
	$phpWord->addFontStyle('headerStyle',array(
		'name'=>'Arial', 
		'size'=>10, 
		'color'=>'444477'
	));


	// Defining font style for display values
	$phpWord->addFontStyle('displayValueStyle',array(
		'name'=>'Arial', 
		'size'=>10, 
		'color'=>'000000'
	));
    	$styleHeaderFont = array('bold'=>true, 'size'=>10, 'name'=>'Arial');
    	//$styleBundleNameFont = array('bold'=>false, 'underline'=>'single', 'color'=>'666666', 'size'=>11, 'name'=>'Arial');
    	$styleBundleNameFont = array('bold'=>false, 'size'=>10, 'name'=>'Arial');
	$styleContentFont = array('bold'=>false, 'size'=>10, 'name'=>'Arial');
	$styleContentFontT = array('bold'=>false, 'size'=>7, 'name'=>'Tahoma');
	$styleContentFontS = array('bold'=>false, 'size'=>8, 'name'=>'Arial');
	$styleContentFontU = array('bold'=>false, 'underline'=>'single', 'size'=>10, 'name'=>'Arial');
	$styleContentFontB = array('bold'=>true, 'size'=>10, 'name'=>'Arial');
	$styleContentFontL = array('bold'=>true, 'size'=>14, 'name'=>'Arial');
	$styleMarginFont = array('bold'=>true, 'size'=>10, 'name'=>'Arial');
	$noSpace = array('spaceAfter'=>0);	

	// Define table style arrays
	$styleTable = array('borderSize'=>0, 'borderColor'=>'ffffff', 'cellMargin'=>0);
	$styleFirstRow = array('borderBottomSize'=>0,'borderBottomColor'=>'ffffff');

	// Define cell style arrays
	$styleCell = array('valign'=>'left');
	$styleCellBTLR = array('valign'=>'left');

	// Define font style for first row
	$fontStyle = array('bold'=>true, 'align'=>'left');

	// Add table style
	$phpWord->addTableStyle('myOwnTableStyle', $styleTable, $styleFirstRow);

	$blank = caEscapeForXML(html_entity_decode(strip_tags(br2nl(''))));

//*****************************************************
	$section = $phpWord->addSection($sectionStyle);

    	// Add header for all pages
    	$header = $section->addHeader();
	
	$headertable = $header->addTable('myOwnTableStyle');

	//heading row
	$headertable->addRow();

	// First  column :
		$contentCell = $headertable->addCell(2 * $cmToTwips);
		$contentCell->addText('Datum: ', $styleContentFontT, array('align'=>'center', 'spaceAfter'=>2));
		$contentCell->addText('Contactpersoon: ', $styleContentFontT, array('center'=>'right', 'spaceAfter'=>2));
		$contentCell->addText('Telefoon/fax: ', $styleContentFontT, array('align'=>'center', 'spaceAfter'=>2));
		$contentCell->addText('E-mail: ', $styleContentFontT, array('align'=>'center', 'spaceAfter'=>2));

	//Second Column : Bruikleenovereenkomst
		$contentCell = $headertable->addCell(10 * $cmToTwips);
		$contentCell->addText(date("d m Y"), $styleContentFontS, $noSpace);
		$contentCell->addText('Ir. Sylvia Nijhuis', $styleContentFontS, $noSpace);
		$contentCell->addText('+31 (0)15 27 84357', $styleContentFontS, $noSpace);
		$contentCell->addText('s.m.nijhuis@tudelft.nl', $styleContentFontS, $noSpace);

	//Third Column : Adres
		$cellRowSpan = array('vMerge' => 'restart', 'valign' => 'top', 'bgColor' => 'ffffff');

		$contentCell = $headertable->addCell(8 * $cmToTwips, $cellRowSpan);
    		$headerimage = $this->request->getThemeDirectoryPath()."/graphics/logos/".$this->request->config->get('letter_img');
    		#$headerimage = $this->request->getThemeDirectoryPath()."/graphics/logos/".$this->request->config->get('report_img');
			if(file_exists($headerimage)){
				$contentCell->addImage($headerimage,array('height' => 70,'wrappingStyle' => 'inline','alignment' => 'left'));
			}

    	// Add footer
    	$footer = $section->addFooter();
    	$footer->addPreserveText('Pag./van {PAGE}/{NUMPAGES}', $styleContentFontT, array('align' => 'right'));

//***********************************************************
// pagina 1
//*********************************************************** 
	$phpWord->addTableStyle('myOwnTableStyle', $styleTable, $styleFirstRow);
	
	$table = $section->addTable('myOwnTableStyle');
//-----------------------------------------------------
	//first row 6 lijnen
	$table->addRow();

	// First  column : blank
		$contentCell = $table->addCell(3 * $cmToTwips);
		$contentCell->addText($blank, $styleMarginFont, $noSpace);

	//Second Column : Bruikleenovereenkomst
		$contentCell = $table->addCell(16 * $cmToTwips);
		$contentCell->addText($blank, $styleContentFont);
		$contentCell->addText($blank, $styleContentFont);
		$contentCell->addText(caEscapeForXML(html_entity_decode(strip_tags(br2nl('Bruikleenovereenkomst')))), $styleContentFontB, $noSpace);
		$contentCell->addText($blank, $styleContentFont);
		$contentCell->addText($blank, $styleContentFont);

	//Third Column : Adres
		$contentCell = $table->addCell(4 * $cmToTwips);
		$contentCell->addText('Technische Universiteit Delft', $styleContentFontB, $noSpace);
		$contentCell->addText('___________________________________', $styleContentFontB, $noSpace);
		$contentCell->addText($blank, $styleContentFont);
		$contentCell->addText('Universiteitsdienst', $styleContentFont, $noSpace);
		$contentCell->addText('TU Library', $styleContentFont, $noSpace);
		//$contentCell->addText(caEscapeForXML(html_entity_decode(strip_tags(br2nl('Universiteitsdienst <br>TU Library')))), $styleContentFont);
		//$contentCell->addText(caEscapeForXML(html_entity_decode(strip_tags(br2nl('Universiteitsdienst <br>TU Library'), '<br>'), ENT_QUOTES | ENT_HTML5)), $styleContentFont);
		//$contentCell->addText(caEscapeForXML(html_entity_decode(strip_tags(br2nl('TU Library')))), $styleContentFont);
		$contentCell->addText($blank, $styleContentFont);
//-------------------------------------------------------------
	//Second row
	$table->addRow();

	// First  column : Aan
		$contentCell = $table->addCell(3 * $cmToTwips);
		$contentCell->addText(caEscapeForXML(html_entity_decode(strip_tags(br2nl('Aan')))), $styleContentFont, $noSpace);
		$contentCell->addText($blank, $styleContentFont, $noSpace);
		$contentCell->addText(caEscapeForXML(html_entity_decode(strip_tags(br2nl('Ter attentie')))), $styleContentFont, $noSpace);

	//Second Column : Related entity
		$contentCell = $table->addCell(10 * $cmToTwips);
		$contentCell->addText($nemer, $styleContentFont, $noSpace);
		$contentCell->addText($blank, $styleContentFont, $noSpace);
		$contentCell->addText($attentie, $styleContentFont, $noSpace);

	//Third Column : Adres
		$contentCell = $table->addCell(6 * $cmToTwips);
		$contentCell->addText(caEscapeForXML(html_entity_decode(strip_tags(br2nl('Adres')))), $styleContentFont, $noSpace);
		$contentCell->addText(caEscapeForXML(html_entity_decode(strip_tags(br2nl('Prometheusplein 1')))), $styleContentFont, $noSpace);
		$contentCell->addText(caEscapeForXML(html_entity_decode(strip_tags(br2nl('2628 ZC Delft')))), $styleContentFont, $noSpace);

//---------------------------------------------------------------
	//Third Row
	$table->addRow();

	// First  column : Blank
		$contentCell = $table->addCell(3 * $cmToTwips);
		$contentCell->addText($blank, $styleHeaderFont);
		$contentCell->addText($blank, $styleHeaderFont);
		//$contentCell->addText($blank, $styleHeaderFont);


//new table with only one column
//*****************************************************
	$phpWord->addTableStyle('myOwnTableStyle1', $styleTable, $styleFirstRow);

	$table1 = $section->addTable('myOwnTableStyle1');

	$table1->addRow();

	$Cell = $table1->addCell(3 * $cmToTwips);
	$Cell->addText($blank, $styleContentFont);

	$Cell = $table1->addCell(20 * $cmToTwips);
	$Cell->addText(caEscapeForXML(html_entity_decode(strip_tags(br2nl('-  OVEREENKOMST  -')))), $styleContentFontL, array('align'=>'center'));
	$Cell->addText($blank, $styleContentFont);
	$Cell->addText($blank, $styleContentFont);

//-----------------------------------------------
	$Cell->addText('Ondergetekenden:', $styleContentFont);
	# $Cell->addText($blank, $styleContentFont);
	
	$Textrun = $Cell->addTextrun('pStyle');
	$Textrun->addText('Technische Universiteit Delft', $styleContentFontB, $noSpace);
	$Textrun->addText(', Universiteitsdienst, TU Delft Library', $styleContentFont, $nospace);

	$Cell->addText('Prometheusplein 1', $styleContentFont, $noSpace);
	$Cell->addText('2628 ZC te Delft', $styleContentFont);
	# $Cell->addText($blank, $styleMarginFont);

	$Cell->addText('ten deze vertegenwoordigd door S.M. Nijhuis, conservator Academisch Erfgoed', $styleContentFont);
	# $Cell->addText($blank, $styleMarginFont);

	$Cell->addText('hierna te noemen: bruikleengever,', $styleContentFontU);
	$Cell->addText('en', $styleContentFont);

	$Cell->addText($nemer, $styleContentFontB, $noSpace);
	$Cell->addText($straat, $styleContentFont, $noSpace);
	$Cell->addText($plaats, $styleContentFont);

	$Cell->addText('ten deze vertegenwoordigd door '. $attentie . ', ', $styleContentFont);

	$Cell->addText('hierna te noemen: bruikleennemer,', $styleContentFontU);

	$Cell->addText('hierna gezamenlijk te noemen: de partijen,', $styleContentFont);

	$Cell->addText('komen als volgt overeen:', $styleContentFont);

	$Cell->addText('Overwegende dat:', $styleContentFont, $noSpace);

	$Cell->addListItem('Erfgoedinstellingen de maatschappelijke plicht hebben om de aan hen toevertrouwde voorwerpen zo veelvuldig als mogelijk is te presenteren aan het publiek dan wel voor onderzoek en ten behoeve van educatieve doeleinden beschikbaar te stellen;', 0, $styleContentFont, $noSpace);
	$Cell->addListItem('Erfgoedinstellingen de plicht hebben om de aan hen toevertrouwde voorwerpen zo goed mogelijk tegen verval, beschadiging en vermissing te beschermen;', 0, $styleContentFont, $noSpace);
	$Cell->addListItem('Bruiklenen slechts worden gegeven wanneer dit in alle redelijkheid past binnen de aard en de omvang van het doel waarvoor het bruikleen wordt gevraagd,', 0, $styleContentFont);

	$Cell->addText('Definities:', $styleContentFont, $noSpace);

	$Cell->addListItem('Bruikleen: het door de ene erfgoed beherende instelling in bruikleen geven en door een andere erfgoed beherende instelling in bruikleen nemen van één of meerdere objecten.', 0, $styleContentFont, $noSpace);
	$Cell->addListItem('Object: het voorwerp of de voorwerpen dat / die in bruikleen wordt / worden gegeven.', 0, $styleContentFont);


//***********************************************************
// pagina 2
//***********************************************************
	$section->addPageBreak();

	$phpWord->addTableStyle('myOwnTableStyle2', $styleTable, $styleFirstRow);

	$ListStyle1 = array('listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER, 'numId' => 1);
	$ListStyle2 = array('listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER, 'numId' => 2);
	$ListStyle3 = array('listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER, 'numId' => 3);
	$ListStyle4 = array('listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER, 'numId' => 4);
	$ListStyle5 = array('listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER, 'numId' => 5);
	$ListStyle6 = array('listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER, 'numId' => 6);
	$ListStyle7 = array('listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER, 'numId' => 7);

	$table2 = $section->addTable('myOwnTableStyle2');

	$table2->addRow();

	$Cell2 = $table2->addCell(3 * $cmToTwips);
	$Cell2->addText($blank, $styleMarginFont);
	$Cell2 = $table2->addCell(18 * $cmToTwips);
 //-----------------------------------------------------------
	$Cell2->addText('Artikel 1. Inhoud van de overeenkomst', $styleContentFontB);
	$Cell2->addListItem('Bruikleengever geeft aan bruikleennemer in bruikleen de objecten zoals vermeld in de aan deze overeenkomst gehechte bijlage;', 0, $styleContentFont, $ListStyle1, $noSpace);
	$Cell2->addListItem('Het bruikleen vindt plaats om niet;', 0, $styleContentFont, $ListStyle1, $noSpace);
	$Cell2->addListItem('Het bruikleen wordt ter beschikking gesteld ten behoeve van ??? ', 0, $styleContentFont, $ListStyle1);

	//++++++++++++++++++++++
	# $Cell2->addText($blank, $styleMarginFont);
	$Cell2->addText('Artikel 2. Duur van de overeenkomst', $styleContentFontB);
	$Cell2->addListItem('Het bruikleen vangt aan op de datum dat het voorwerp bij de bruikleengever wordt afgehaald, zijnde ??? ;', 0, $styleContentFont, $ListStyle2, $noSpace);
	$Cell2->addListItem('Deze overeenkomst is aangegaan voor de periode van ??? ;', 0, $styleContentFont, $ListStyle2, $noSpace);
	$Cell2->addListItem('De overeenkomst wordt niet stilzwijgend verlengd;', 0, $styleContentFont, $ListStyle2, $noSpace);
	$Cell2->addListItem('Eventuele verlenging kan in een nieuwe overeenkomst worden vastgelegd.', 0, $styleContentFont, $ListStyle2);

	//++++++++++++++++++++++
	# $Cell2->addText($blank, $styleMarginFont);
	$Cell2->addText('Artikel 3. Vervoer en Verpakking', $styleContentFontB);
	$Cell2->addListItem('De transportkosten van en naar het centrale depot van de TU Delft te Delft komen voor rekening van de bruikleennemer;', 0, $styleContentFont, $ListStyle3, $noSpace);
	$Cell2->addListItem('Het object dient in een gesloten vrachtauto door een gekwalificeerde transporteur vervoerd te worden.', 0, $styleContentFont, $ListStyle3);

	//++++++++++++++++++++
	#$Cell2->addText($blank, $styleMarginFont);
	$Cell2->addText('Artikel 4. Gebruik', $styleContentFontB);
	$Cell2->addListItem('Bruikleennemer zal het object uitsluitend gebruiken op de in artikel 1.3 genoemde locatie. Het object mag slechts overgebracht worden naar een andere locatie na schriftelijke goedkeuring van bruikleengever;', 0, $styleContentFont, $ListStyle4, $noSpace);
	$Cell2->addListItem('Bruikleennemer zal het object uitsluitend gebruiken voor het in artikel 1.3 genoemde doel;', 0, $styleContentFont, $ListStyle4, $noSpace);
	$Cell2->addListItem('Bruikleennemer voert het toezicht over de ruimte waarin het object wordt gebruikt en zorgt voor voldoende beveiligingsmaatregelen tegen diefstal en molest;', 0, $styleContentFont, $ListStyle4, $noSpace);
	$Cell2->addListItem('Bruikleennemer draagt zorg voor geschikte conditionering van de ruimte waaronder wordt verstaan:', 0, $styleContentFont, $ListStyle4, $noSpace);
	$Cell2->addListItem('Luchtvochtigheid tussen 45% en 55%', 1, $styleContentFont, $ListStyle4, $noSpace);
	$Cell2->addListItem('Temperatuur tussen 17°C en 20°° C', 1, $styleContentFont, $ListStyle4, $noSpace);
	$Cell2->addListItem('Lichtintensiteit van maximaal 150 Lux; UV maximaal 50 Lumen/Watt', 1, $styleContentFont, $ListStyle4, $noSpace);
	$Cell2->addListItem('Vrij van ongedierte', 1, $styleContentFont, $ListStyle4, $noSpace);
	$Cell2->addListItem('Afgezien van het bepaalde in artikel 4.3 en 4.4 doet bruikleennemer alles wat van een goed bruikleennemer mag worden verwacht om het object op zo zorgvuldig mogelijke wijze te behandelen.', 0, $styleContentFont, $ListStyle4);

	//++++++++++++++++++++
	#$Cell2->addText($blank, $styleMarginFont);
	$Cell2->addText('Artikel 5. Conditierapporten', $styleContentFontB);
	$Cell2->addListItem('Het bruikleen verkeert in goede staat.', 0, $styleContentFont, $ListStyle5);

	//++++++++++++++++++++
	#$Cell2->addText($blank, $styleMarginFont);
	$Cell2->addText('Artikel 6. Restauratie / Conservering', $styleContentFontB);
	$Cell2->addListItem('Bruikleennemer zal nimmer zonder voorafgaande schriftelijke toestemming van bruikleengever een in bruikleen gegeven object (laten) restaureren, in- of uitlijsten, schoonmaken of anderszins iets aan het object wijzigen tenzij er voor het vragen en verkrijgen van toestemming geen tijd is vanwege een spoedeisende omstandigheid en deze spoedeisende omstandigheid met zich meebrengt dat directe actie moet worden ondernomen. Bruikleennemer zal in dit laatste geval alles wat redelijkerwijs in zijn vermogen ligt in het werk stellen voor het behoud van het object;', 0, $styleContentFont, $ListStyle6, $noSpace);
	$Cell2->addListItem('Indien bruikleengever vooraf toestemming geeft voor restauratie wordt tegelijk met het geven van de toestemming afgesproken voor wiens rekening en risico de restauratie is.', 0, $styleContentFont, $ListStyle6);

//***********************************************************
// pagina 3
//***********************************************************
	$section->addPageBreak();

	$phpWord->addTableStyle('myOwnTableStyle3', $styleTable, $styleFirstRow);

	$ListStylea1 = array('listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER, 'numId' => 8);
	$ListStylea2 = array('listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER, 'numId' => 9);
	$ListStylea3 = array('listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER, 'numId' => 10);
	$ListStylea4 = array('listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER, 'numId' => 11);
	$ListStylea5 = array('listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER, 'numId' => 12);

	$table3 = $section->addTable('myOwnTableStyle3');

	$table3->addRow();

	$Cell3 = $table3->addCell(3 * $cmToTwips);
	$Cell3->addText($blank, $styleMarginFont);
	$Cell3 = $table3->addCell(18 * $cmToTwips);

 //-----------------------------------------------------------
	$Cell3->addText('Artikel 7. Intellectuele eigendom', $styleContentFontB);
	$Cell3->addListItem('De bruikleennemer wordt eenmalig toegestaan het object vast te leggen op foto, film of op digitale wijze;', 0, $styleContentFont, $ListStylea1, $noSpace);
	$Cell3->addListItem("Indien bruikleennemer gebruik wenst te maken van bestaande foto's, films of digitaal materiaal van het object waarop (auteurs)rechten van rechthebbende(n) rusten, zal bruikleennemer deze auteursrechten respecteren en met de rechthebbende(n) overleggen over een eventuele vergoeding voor gebruik van het materiaal;", 0, $styleContentFont, $ListStylea1, $noSpace);
	$Cell3->addListItem('Bruikleennemer zal te allen tijde de eventueel nog bestaande auteursrechten van de rechthebbende(n) van het object respecteren.', 0, $styleContentFont, $ListStylea1);

	//++++++++++++++++++++++
	$Cell3->addText('Artikel 8. Risico bruikleen', $styleContentFontB);
	$Cell3->addListItem('Het bruikleen geschiedt voor rekening en risico van bruikleennemer;', 0, $styleContentFont, $ListStylea2, $noSpace);
	$Cell3->addListItem("Bruikleennemer draagt zorg voor het contractueel neerleggen van de risicoaansprakelijkheid, voor zover van toepassing, bij externe bedrijven die risicovolle werkzaamheden uitoefenen, waaronder, maar niet beperkt tot, schoonmaakbedrijven. Ter beperking van deze risico's stelt bruikleennemer veiligheidsvoorschriften op;", 0, $styleContentFont, $ListStylea2, $noSpace);
	$Cell3->addListItem('Bruikleennemer draagt zorg voor het contractueel neerleggen van de risicoaansprakelijkheid tijdens het vervoer bij het vervoersbedrijf;', 0, $styleContentFont, $ListStylea2, $noSpace);
	$Cell3->addListItem('Bruikleennemer zal bij verlies of beschadiging van een object bruikleengever hiervan onmiddellijk in kennis stellen;', 0, $styleContentFont, $ListStylea2, $noSpace);
	$Cell3->addListItem('Bruikleennemer is niet aansprakelijk voor de normale slijtage van een object en schade die veroorzaakt is door een omstandigheid die niet te verwijtbaar is aan bruikleennemer.', 0, $styleContentFont, $ListStylea2);

	//++++++++++++++++++++++
	$Cell3->addText('Artikel 9. Verzekering', $styleContentFontB);
	$Cell3->addListItem("De bruikleennemer verzekert het object volgens de in het inter-museale verkeer geldende verzekeringsvoorwaarden 'van spijker tot spijker';", 0, $styleContentFont, $ListStylea3, $noSpace);
	$Cell3->addListItem('De door bruikleennemer te verzekeren waarden staan vermeld in de bijlage.', 0, $styleContentFont, $ListStylea3);

	//++++++++++++++++++++
	$Cell3->addText('Artikel 10. Tussentijdse beëindiging', $styleContentFontB);
	$Cell3->addListItem('Indien bruikleennemer het object niet behandelt volgens de bepalingen in deze overeenkomst, is bruikleengever gerechtigd de bruikleenovereenkomst met onmiddellijke ingang en zonder tussenkomst van een rechterlijke instantie te beëindigen en te verlangen dat bruikleennemer ervoor zorg draagt dat het object terstond weer bij bruikleengever in bezit komt.', 0, $styleContentFont, $ListStylea4);

	//++++++++++++++++++++
	$Cell3->addText('Artikel 11. Terugnamebevoegdheid', $styleContentFontB);
	$Cell3->addListItem('In het geval bruikleengever voor de einddatum van deze bruikleenovereenkomst een object zelf dringend nodig heeft als gevolg van een omstandigheid die bij het aangaan van deze bruikleenovereenkomst redelijkerwijs niet was te voorzien, deelt bruikleengever dit schriftelijk en met redenen omkleed mee aan bruikleennemer. Bruikleennemer zal hierop zo spoedig als redelijkerwijs mogelijk het object afstaan aan bruikleengever;', 0, $styleContentFont, $ListStylea5, $noSpace);
	$Cell3->addListItem('Bruikleennemer kan het object voor de einddatum van deze bruikleenovereenkomst op grond van bij het aangaan van deze overeenkomst redelijkerwijs niet te voorziene, dringende omstandigheid teruggeven aan bruikleengever mits bruikleengever aangeeft dat hij opslagruimte beschikbaar heeft voor het object. Is dit niet het geval, dan zal bruikleennemer het object voor eigen rekening in een daartoe passende opslagruimte opslaan tot het einde van de bruikleenovereenkomst of zoveel eerder als bruikleengever aangeeft.', 0, $styleContentFont, $ListStylea5);

//***********************************************************
// pagina 4
//***********************************************************
	$section->addPageBreak();

	$phpWord->addTableStyle('myOwnTableStyle4', $styleTable, $styleFirstRow);

	$ListStyleb1 = array('listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER, 'numId' => 13);
	$ListStyleb2 = array('listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER, 'numId' => 14);

	$table4 = $section->addTable('myOwnTableStyle4');

	$table4->addRow();

	$Cell4 = $table4->addCell(3 * $cmToTwips);
	$Cell4->addText($blank, $styleMarginFont);
	$Cell4 = $table4->addCell(18 * $cmToTwips);
 //-----------------------------------------------------------
	$Cell4->addText('Artikel 12. Gedragslijn Museale Beroepsethiek', $styleContentFontB);
	$Cell4->addListItem('Zowel bruikleennemer als bruikleengever houden zich aan hetgeen is bepaald in de Gedragslijn Museale Beroepsethiek.', 0, $styleContentFont, $ListStyleb1);

	//++++++++++++++++++++++
	$Cell4->addText('Artikel 13. Geschillen', $styleContentFontB);
	$Cell4->addListItem('Op deze overeenkomst is Nederlands recht van toepassing.', 0, $styleContentFont, $ListStyleb2);

	//++++++++++++++++++++++
	#$Cell->addText($straat, $styleContentFont, $noSpace);
	$Cell4->addText($blank, $styleContentFont);
	$Cell4->addText($blank, $styleContentFont);
	$Cell4->addText($blank, $styleContentFont);
	$Cell4->addText('Aldus overeengekomen en in tweevoud opgemaakt,', $styleContentFont, $noSpace);
	$Cell4->addText($blank, $styleContentFont);
	$Cell4->addText($blank, $styleContentFont);

	//++++++++++++++++++++++
	$table5 = $section->addTable('myOwnTableStyle5');

	$table5->addRow();

	$Cell5 = $table5->addCell(2.78 * $cmToTwips);
	$Cell5->addText($blank, $styleMarginFont);

	$Cell5 = $table5->addCell(8 * $cmToTwips);
	$Cell5->addText('Sylvia Nijhuis', $styleContentFont);
	$Cell5->addText($blank, $styleMarginFont);
	$Cell5->addText($blank, $styleMarginFont);
	$Cell5->addText('', $styleContentFont, $noSpace);
	$Cell5->addText('Datum:', $styleContentFont, $noSpace);
	$Cell5->addText('Plaats:', $styleContentFont, $noSpace);

	$Cell5 = $table5->addCell(8 * $cmToTwips);
	$Cell5->addText($attentie, $styleContentFont);
	$Cell5->addText($blank, $styleMarginFont);
	$Cell5->addText($blank, $styleMarginFont);
	$Cell5->addText('', $styleContentFont, $noSpace);
	$Cell5->addText('Datum:', $styleContentFont, $noSpace);
	$Cell5->addText('Plaats:', $styleContentFont, $noSpace);

//*********************************************************
 	// Finally, write the document:
	$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
 	header("Content-Type:application/vnd.openxmlformats-officedocument.wordprocessingml.document");
	header('Content-Disposition:inline;filename=Export.docx ');
 	
 	//$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'RTF');
 	//header("Content-type: application/rtf");
 	//header('Content-Disposition:inline;filename=Export.rtf ');
 	
	//$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'ODText');
	//header('Content-Type: application/vnd.oasis.opendocument.text');
	//header('Content-Disposition:inline;filename=Export.odt ');
 	
 	$objWriter->save('php://output');
