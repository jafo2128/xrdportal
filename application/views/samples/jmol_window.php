<?php

//echo "<script type='text/javascript' src='http://chemapps.stolaf.edu/jmol/jmol.php?model=Tetrahydrocannabinol&width=500px&height=500px&inline'></script>";


echo( "<!DOCTYPE html><html lang='en'><head><script type='text/javascript' src='".base_url('/js/Jmol.js')."'></script></head><body><form><script type='text/javascript'>jmolInitialize('".base_url('/lib/jmol/jsmol/java')."'); jmolApplet(500, 'load ".base_url('/images/uploads')."/".$file['name']."');</script></form></body></html>");

?>
