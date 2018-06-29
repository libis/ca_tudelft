<?php
/* ----------------------------------------------------------------------
 * app/templates/summary/summary.php
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2014 Whirl-i-Gig
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
 * @name Collection summary
 * @type page
 * @pageSize letter
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
 
 	$t_item = $this->getVar('t_subject');
	
	$va_bundle_displays = $this->getVar('bundle_displays');
	$t_display = $this->getVar('t_display');
	$va_placements = $this->getVar("placements");

	print $this->render("pdfStart.php");
	print $this->render("header.php");
	print $this->render("footer.php");	
?><br>
	<div class="title">
		<?php print $t_item->getLabelForDisplay();?>
	</div>

<?php
    print '<div class="data"><span class="label">idno'." </span><span class='meta'> {$t_item->getWithTemplate('^ca_collections.idno')}</span></div>\n";
    print '<div class="data"><span class="label">naam'." </span><span class='meta'> {$t_item->getWithTemplate('^ca_collections.hierarchy.preferred_labels.name%delimiter=__➔__')}</span></div>\n";
    $objects = $t_item->getRelatedItems('ca_objects');
    $t_object = new ca_objects();

    print '<div class="data"><span class="label">gerelateerde objecten'." </span></div>";
    foreach ($objects as $item => $object ){
        $t_object->load($object['object_id']);
        $datering = $t_object->get('ca_objects.objectProductionDate');
        $width = $t_object->get('ca_objects.dimensions.dimensions_width');
        $height = $t_object->get('ca_objects.dimensions.dimensions_height');
        $depth = $t_object->get('ca_objects.dimensions.dimensions_depth');

        $image_reps = array();
        //$image_reps = $t_object->getRepresentations(array("thumbnail"), null, array('return_primary_only' => true));
        if(!empty($image_reps) && is_array($image_reps))
            $image = current($image_reps);

        print '<div style="page-break-inside: avoid;">';

            print '<div style="page-break-inside: avoid; float:left; width: 30%; height: 60px; margin:10px; padding-top: 5px">';
                if(!empty($image))
                    print '<img src="data:image/jpeg;base64,'.base64_encode(file_get_contents($image['paths']['thumbnail'])).' width="70" height="70"">'."\n";
            print '</div>';

            print '<div style="font-size: 12px; page-break-inside: avoid; padding-top: 5px; float:left; margin:10px;width: 50%">';
                print "{$object['idno']}<br>{$object['name']}<br>Datering: {$datering}<br>";
                if(!empty($width))
                    print "{$width} B x ";
                if(!empty($height))
                    print "{$height} H x ";
                if(!empty($depth))
                    print "{$depth} D";
            print '</div>';

        print '</div>';
    }
	print $this->render("pdfEnd.php");
