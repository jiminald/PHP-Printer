<?php
    
    /* Set a few generic settings for debugging */
	ini_set('display_errors', 'on');
	error_reporting(E_ALL);
    ini_set('magic_quotes_runtime', false);
    
    /* Load the 3Core CLI Output */
    date_default_timezone_set('Europe/London');
    require_once 'Output_CLI.php';
    $Output = new ThreeCore_Output_CLI;
    
    /* Load the Printer Class */
    include 'printer.class.php';
    $printer = new Printer;
    
    $Output->screen('Loading internal list of Printers');
    #print_r($printer->enumerate());
    
    $Output->screen('Select Printer. It is: '.$printer->select('PRINTER NAME'));
    $Output->screen('Setting Document Title to: '.$printer->document_title('PHP Printer Interface'));
    #$Output->screen('Setting Orientation to: '.$printer->orientation('portrait'));
    #$Output->screen('Setting Copies to : '.$printer->copies(2));
    $printer->write('Success!<br />PHP Printer Test Page.');
    
    $Output->screen('ACTUALLY Printing Document');
    $printer->print_buffer();
?>
