<?php
/* ----------------------------------------------------------------------
 * app/templates/checklist.php
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2014-2015 Whirl-i-Gig
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
 * @name PDF (bruikleen)
 * @type page
 * @pageSize a4
 * @pageOrientation portrait
 * @tables ca_objects
 *
 * @marginTop 0.75in
 * @marginLeft 0.25in
 * @marginBottom 0.5in
 * @marginRight 0.25in
 *
 * ----------------------------------------------------------------------
 */

	$t_display				= $this->getVar('t_display');
	$va_display_list 		= $this->getVar('display_list');
	$vo_result 				= $this->getVar('result');
	$vn_items_per_page 		= $this->getVar('current_items_per_page');
	$vs_current_sort 		= $this->getVar('current_sort');
	$vs_default_action		= $this->getVar('default_action');
	$vo_ar					= $this->getVar('access_restrictions');
	$vo_result_context 		= $this->getVar('result_context');
	$vn_num_items			= (int)$vo_result->numHits();
	
	$vn_start 				= 0;

	print $this->render("pdfStart.php");
    	//libis_start
    	// in order to use header on each page with wkhtmltopdf tool, we need to provide a separate header html file, in app/lib/ca/BaseFindController.php.
	if($this->getVar('PDFRenderer') != "wkhtmltopdf")
        	print $this->render("header.php");
	    //libis_end
	print $this->render("footer.php");
?>
		<div id='body'>
            <table cellspacing="10">
                <tr style="align-content: center">
                    <th width="20px"><?php print "Nr."; ?></th>
                    <th width="180px"><?php print "Foto"; ?></th>
                    <th width="100px"><?php print "Inv. Nr."; ?></th>
                    <th width="180px"><?php print "Titel"; ?></th>
                    <th width="80px"><?php print "Afmetingen"; ?></th>
                </tr>
            </table>

<?php

		$vo_result->seek(0);
		
		$vn_line_count = 1;
		while($vo_result->nextHit()) {
			$vn_object_id = $vo_result->get('ca_objects.object_id');		
?>
            <div class="row">
                <table cellspacing="10">
			<tr>
                <td width="20px" align="left"><?php print $vn_line_count++ ?></td>
				<td>
<?php 
					if ($vs_path = $vo_result->getMediaPath('ca_object_representations.media', 'preview')) {
						print '<img src="data:image/jpeg;base64,'.base64_encode(file_get_contents($vs_path)).'">';
					} else {
?>
						<?php
                            print '<img src="data:image/jpeg;base64,'.base64_encode(file_get_contents($this->request->getThemeDirectoryPath()."/graphics/logos/image_placeholder.png")).'">';
                        ?>
<?php					
					}	
?>								

				</td><td width="70%">
					<div class="metaBlock">
                        <table cellspacing="10">
                            <tr style="align-content: left">
                                <td style="width: 20%; padding-right: 10px">
                                    <?php print "<div class='metadata'><span class='displayValue' >".$vo_result->getWithTemplate('^ca_objects.idno')."</span></div>"; ?>
                                </td>
                                <td style="width: 50%; padding-right: 10px">
                                    <?php print "<div class='metadata'><span class='displayValue' >".$vo_result->getWithTemplate('^ca_objects.preferred_labels.name')."</span></div>"; ?>
                                </td>
                                <td style="width: 30%; padding-right: 10px">
                                    <?php print "<div class='metadata'><span class='displayValue' >".$vo_result->getWithTemplate('<ifdef code="ca_objects.dimensions.dimensions_width">^ca_objects.dimensions.dimensions_width B<br/></ifde><ifdef code="ca_objects.dimensions.dimensions_depth">^ca_objects.dimensions.dimensions_depth D <br/></ifdef><ifdef code="ca_objects.dimensions.dimensions_height">^ca_objects.dimensions.dimensions_height H</ifdef>')."</span></div>"; ?>
                                </td>
                            </tr>
                        </table>
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