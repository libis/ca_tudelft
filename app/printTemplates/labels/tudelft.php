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
 * @name TUDelft 
 * @type label
 * @pageSize letter
 * @pageOrientation portrait
 * @tables ca_objects
 * @marginLeft 0.5in
 * @marginTop 0.25in
 * @labelWidth 2.3in
 * @labelHeight 1.5in
 *
 * ----------------------------------------------------------------------
 */
 
 	$vo_result = $this->getVar('result');	
 ?>
 <div class="smallText" style="width: 100%; left: 1in; top: 0.1in;">
     <b><?php print $vo_result->getWithTemplate('^ca_objects.idno'); ?></b><br>
     <?php print strtoupper($vo_result->getWithTemplate('^ca_objects.objectName')); ?><br>
     <?php print $vo_result->getWithTemplate('^ca_objects.preferred_labels.name'); ?><br>
     <?php print 'Datering: '.$vo_result->getWithTemplate('^ca_objects.objectProductionDate'); ?><br>
     <?php print $vo_result->getWithTemplate("<unit relativeTo='ca_objects_x_storage_locations' restrictToRelationshipTypes='huidigeStandplaats' delimiter='->'>^ca_storage_locations.hierarchy.preferred_labels.name</unit>");?>

 </div>
