<?php
/* ----------------------------------------------------------------------
 * app/printTemplates/labels/avery_8000.php
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
 * @name TUDelftmet
 * @type label
 * @pageSize a4
 * @pageOrientation landscape
 * @tables ca_objects
 * @verticalGutter 0.0984in
 * @horizontalGutter 0.18in
 * @marginLeft 0.3in
 * @marginTop 0.2854in
 * @labelWidth 1.5in
 * @labelHeight 2.5in
 *
 * ----------------------------------------------------------------------
 */
 
 	$vo_result = $this->getVar('result');
 ?>
 <div class="smallText" style="width: 90%; word-wrap: break-word;clear:both; ">
     <div style="padding-bottom: 5px">
         <?php
            if(!empty($vo_result->get('ca_object_representations.media.thumbnail', array('returnURL' => true))))
                print '<img style="border:non" width=\'60\' height=\'70\' src="data:image/jpeg;base64,'.base64_encode(file_get_contents($vo_result->get('ca_object_representations.media.thumbnail', array('returnURL' => true)))).'">';
            else
                print '<img style="border:non" width=\'60\' height=\'70\' src="data:image/jpeg;base64,'.base64_encode(file_get_contents($this->request->getThemeDirectoryPath()."/graphics/logos/image_placeholder.png")).'">';
         ?>
     </div>

     <div >
     <b><?php print $vo_result->getWithTemplate('^ca_objects.idno'); ?></b><br>
     <?php print strtoupper($vo_result->getWithTemplate('^ca_objects.objectName')); ?><br>
     <?php print $vo_result->getWithTemplate('^ca_objects.preferred_labels.name'); ?><br>
     <?php print 'Datering: '.$vo_result->getWithTemplate('^ca_objects.objectProductionDate'); ?><br>
     <?php print $vo_result->getWithTemplate("<unit relativeTo='ca_objects_x_storage_locations' restrictToRelationshipTypes='huidigeStandplaats' delimiter='->'>^ca_storage_locations.hierarchy.preferred_labels.name</unit>");?>
     </div>
 </div>


