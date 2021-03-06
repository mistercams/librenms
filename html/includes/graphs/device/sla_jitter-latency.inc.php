<?php
/*
 * LibreNMS module to Graph Cisco IPSLA UDP Jitter metrics 
 *
 * Copyright (c) 2016 Aaron Daniels <aaron@daniels.id.au>
 *
 * This program is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the
 * Free Software Foundation, either version 3 of the License, or (at your
 * option) any later version.  Please see LICENSE.txt at the top level of
 * the source code distribution for details.
 */

$sla = dbFetchRow('SELECT `sla_nr` FROM `slas` WHERE `sla_id` = ?', array($vars['id']));

require 'includes/graphs/common.inc.php';
$rrd_options .= ' -l 0 -E ';
$rrd_filename = rrd_name($device['hostname'], array('sla', $sla['sla_nr'], 'jitter'));

if (rrdtool_check_rrd_exists($rrd_filename)) {
    $rrd_options .= " COMMENT:'                          Cur    Min    Max    Avg\\n'";

    $rrd_options .= " DEF:SD=" . $rrd_filename . ":OWAvgSD:AVERAGE ";
    $rrd_options .= " LINE1.25:SD#0000ee:'Src to Dst (ms)    ' ";
    $rrd_options .= " GPRINT:SD:LAST:'%5.2lf' ";
    $rrd_options .= " GPRINT:SD:MIN:'%5.2lf' ";
    $rrd_options .= " GPRINT:SD:MAX:'%5.2lf' ";
    $rrd_options .= " GPRINT:SD:AVERAGE:'%5.2lf'\\\l ";

    $rrd_options .= " DEF:DS=" . $rrd_filename . ":OWAvgDS:AVERAGE ";
    $rrd_options .= " LINE1.25:DS#008C00:'Dst to Src (ms)    ' ";
    $rrd_options .= " GPRINT:DS:LAST:'%5.2lf' ";
    $rrd_options .= " GPRINT:DS:MIN:'%5.2lf' ";
    $rrd_options .= " GPRINT:DS:MAX:'%5.2lf' ";
    $rrd_options .= " GPRINT:DS:AVERAGE:'%5.2lf'\\\l ";
}
