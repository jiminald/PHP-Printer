<?php

/* Both these tests were takes from the php.net website and comments */

/* Basic test */
$handle = printer_open('PRINTER NAME');
printer_write($handle, "Text to print");
printer_close($handle);
exit;
    
/* Test if it can print its version of WordArt */
$handle = printer_open('PRINTER NAME');
printer_start_doc($handle, "My Document");
printer_start_page($handle);

$font = printer_create_font("Arial", 148, 76, PRINTER_FW_MEDIUM, false, false, false, -50);
printer_select_font($handle, $font);
printer_draw_text($handle, "PHP is simply cool", 40, 40);
printer_delete_font($font);

printer_end_page($handle);
printer_end_doc($handle);
printer_close($handle);
exit;

?>
