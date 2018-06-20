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
 * @pageSize letter
 * @pageOrientation portrait
 * @tables ca_objects
 * @verticalGutter 0.225in
 * @horizontalGutter 0.1125in
 * @marginLeft 0.5in
 * @marginTop 0.15in
 * @labelWidth 2.5in
 * @labelHeight 1.5in
 *
 * ----------------------------------------------------------------------
 */
 
 	$vo_result = $this->getVar('result');
 ?>
 <div class="smallText2" style="width: 90%; word-wrap: break-word;clear:both; ">
     <div class="d1">
     <b><?php print $vo_result->getWithTemplate('^ca_objects.idno'); ?></b><br>
     <?php print strtoupper($vo_result->getWithTemplate('^ca_objects.objectName')); ?><br>
     <?php print $vo_result->getWithTemplate('^ca_objects.preferred_labels.name'); ?><br>
     <?php print 'Datering: '.$vo_result->getWithTemplate('^ca_objects.objectProductionDate'); ?><br>
     <?php print $vo_result->getWithTemplate("<unit relativeTo='ca_objects_x_storage_locations' restrictToRelationshipTypes='huidigeStandplaats' delimiter='->'>^ca_storage_locations.hierarchy.preferred_labels.name</unit>");?>
     </div>
     <div class="d2">
         <?php print '<img width=\'60\' height=\'70\' src="data:image/jpeg;base64,'.base64_encode(file_get_contents($vo_result->get('ca_object_representations.media.thumbnail', array('returnURL' => true)))).'">'; ?>
     </div>
 </div>


