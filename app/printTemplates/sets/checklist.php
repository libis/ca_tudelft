<?php
/* ----------------------------------------------------------------------
 * app/printTemplates/sets/checklist.php
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2014-2017 Whirl-i-Gig
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
 * -=-=-=-=-=- CUT HERE -=-=-=-=-=-
 * Template configuration:
 *
 * @name PDF (checklist)
 * @type page
 * @pageSize letter
 * @pageOrientation portrait
 * @tables ca_objects
 *
 * @marginTop 0.5in
 * @marginLeft 0.25in
 * @marginBottom 0.5in
 * @marginRight 0.25in
 *
 * ----------------------------------------------------------------------
 */

	$vo_result 				= $this->getVar('result');
	$vn_num_items			= (int)$vo_result->numHits();
	$t_set					= $this->getVar("t_set");

    $t_display = new ca_bundle_displays();
    $t_display->load(12); //FIXED display template
    $va_display_list = $t_display->getPlacements();
	
	print $this->render("pdfStart.php");
	print $this->render("header.php");
	print $this->render("footer.php");
?>
<div>
    <?php
    if(file_exists($this->request->getThemeDirectoryPath()."/graphics/logos/".$this->request->config->get('report_img'))){
        print '<img src="'.$this->request->getThemeDirectoryPath().'/graphics/logos/'.$this->request->config->get('report_img').'" class="headerImg"/>';
    }
    ?>
    <br>
</div>
		<div id='body'>
<?php

		$vo_result->seek(0);
		
		$vn_c = 0;
		while($vo_result->nextHit()) {
			$vn_c++;
			$vn_object_id = $vo_result->get('ca_objects.object_id');		
?>
			<div class="row">
			<table>
			<tr>
				<td>
<?php 
					if ($vs_path = $vo_result->getMediaPath('ca_object_representations.media', 'preview')) {
						print "<div class=\"imageTiny\"><img src='{$vs_path}'/></div>";
					} else {
?>
						<div class="imageTinyPlaceholder">&nbsp;</div>
<?php					
					}	
?>								

				</td><td style="width: 70%">
					<div class="metaBlock">
<?php				
					print "<div><span class='meta' style='font-size: 18px; font-weight: bold; text-align: left'>".$vo_result->getWithTemplate('^ca_objects.preferred_labels.name')."</span></div>";

                    foreach($va_display_list as $vn_placement_id => $va_display_item) {
                        if (!strlen($vs_display_value = $t_display->getDisplayValue($vo_result, $vn_placement_id, array('forReport' => true, 'purify' => true)))) {
                            if (!(bool)$t_display->getSetting('show_empty_values')) { continue; }
                            $vs_display_value = "&lt;"._t('not defined')."&gt;";
                        }

                        $label = current($va_display_item['settings']['label']);
                        if(empty($label) || strlen($label)<1){
                            $label = $va_display_item['display'];
                            $pos = strpos($label, "Object");
                            if ($pos !== false) {
                                $label = substr_replace($label, "", $pos, strlen("Object"));
                            }
                        }
                        print '<div class="data"><span class="label">'."{$label} </span><span class='meta'> {$vs_display_value}</span></div>\n";
                    }
?>
					</div>				
				</td>	
			</tr>
			</table>	
			</div>
<?php
		}
?>
		</div>
<?php
	print $this->render("pdfEnd.php");
?>
