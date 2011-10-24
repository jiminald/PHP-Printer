<?php

	/**
	* Output class
	*
	* This uses the smarty templating system for the framework
	* @access       public
	* @author       Jiminald <code@jiminald.co.uk>
	* @copyright    Jiminald 18/May/2010
	* @package      3CoreFrame
	* @subpackage   libraries
	* @version      1.0
	*/

	class ThreeCore_Output_CLI {
		public $screenDateEol = array('date' => TRUE, 'eol' => TRUE);
		public $screenDateOnly = array('date' => TRUE, 'eol' => FALSE);
		public $screenEolOnly = array('date' => FALSE, 'eol' => TRUE);
		public $screenNothing = array('date' => FALSE, 'eol' => FALSE);
		
        public function __construct() {
            ob_start();
        }
		
		public function screen($message, $options = array('date' => TRUE, 'eol' => TRUE)) {
			if ($options['date'] == TRUE) { echo '['.date('d/M/Y H:i:s').'] '; }
			echo $message;
			if ($options['eol'] == TRUE) { echo PHP_EOL; }
			ob_flush();
			flush();
			sleep(1);
		}
        
        public function __destroy() {
            ob_end_clean();
        }
		
	} //End of class
?>
