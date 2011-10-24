<?php
	/**
	 * Print straight to a local printer
	 *
	 * To see more information or get updates, visit the GitHub
     * https://github.com/jiminald/PHP-Printer
	 *
	 * @access       public
	 * @author       Jiminald <code@jiminald.co.uk>
	 * @copyright    Jiminald October 2011
	 * @version      1.0
	 */

    class Printer {
        //Global Variables

		//Public Variables
        public $Output = NULL;
        public $printers = array();
        public $ignore = array();
		//Private Variables
        private $handle = FALSE;
        private $selected = FALSE;
        private $copies = 1;
        private $orientation = 'Portrait';
        private $document_title = '';
        private $buffer = "\r\n ";
		
        /**
         * Set ignore list and scan for printers
         * 
         * @return void 
         */
        public function __construct() {
            //Set ignore list
            $this->ignore = array('Fax', 'Microsoft XPS Document Writer', 'Microsoft Office Document Image Writer');
            
            //Grab printer list
            $this->enumerate();
        } //End of function "__construct"
        
        /**
         * Show Printers Found Connected to this PC
         * 
         * @return array 
         */
        public function enumerate() {
            /* If there are no printers, find them */
            if (count($this->printers) == 0) {
                $printer_list = printer_list(PRINTER_ENUM_LOCAL);
                
                foreach ($printer_list as $printer) {
                    if (in_array($printer['NAME'], $this->ignore) == FALSE) {
                        $this->printers[] = $printer;
                    }
                }
            }
            
            return $this->printers;
        } //End of function "enumerate"
        
        /**
         * Select a Printer to Print to
         * 
         * @param string $printer_name Name of Printer
         * @return string 
         */
        public function select($printer_name = '') {
            /* If no printer specified, return FALSE */
            if ($printer_name == '') { return FALSE; }
            
            /* Scan the Printers array, if the printer asked for is found, then select it, reset values to default and return TRUE */
            foreach ($this->printers as $printer) {
                if ($printer['NAME'] == $printer_name) {
                    $this->selected = $printer_name;
                    $this->copies = 1;
                    $this->orientation = 'Portrait';
                    $this->document_title = '';
                }
            }
            
            /* Return Printer Name, or return FALSE */
            return $this->selected;
        } //End of function "select"
        
        /**
         * Set copy amount
         * 
         * @param integer $copies Copy Amount
         * @return integer 
         */
        public function copies($copies = 0) {
            /* If it is not 0, set the copies */
            if ($copies <> 0) {
                $this->copies = $copies;
            }
            
            /* Return the current value, changed or not */
            return $this->copies;
        } //End of function "copies"
        
        /**
         * Set Page Orientation
         * 
         * @param string $orientation Page Orientation
         * @return string
         */
        public function orientation($orientation = '') {
            /* If it is not blank, set the orientation */
            if ($orientation <> '') {
                $this->orientation = ucwords(strtolower($orientation));
            }
            
            /* Return the current value, changed or not */
            return $this->orientation;
        } //End of function "orientation"
        
        /**
         * Document Title, Or Filename if printing to PDF Printer
         * 
         * @param string $title Page title
         * @return string 
         */
        public function document_title($title = '') {
            /* If it is not blank, set the document Title */
            if ($title <> '') {
                $this->document_title = $title;
            }
            
            /* Return the current value, changed or not */
            return $this->document_title;
        } //End of function "document_title"
        
        /**
         * Write string to Print Buffer
         * 
         * @param string $string Data to save in buffer
         * @return boolean 
         */
        public function write($string) {
            if ($string == '') { return FALSE; }
            
            //Replace <br /> to CRLF
            $string = str_replace(array('<br>', '<br />'), "\r\n ", $string);
            
            $this->buffer .= $string;
            return TRUE;
        } //End of function "write"
        
        /**
         * Print out whats in the buffer
         * 
         * @return boolean 
         */
        public function print_buffer() {
            $connect = $this->_connect();
            if ($connect <> FALSE) {
                $this->_write_buffer();
                $this->_close();
                return TRUE;
            }
            
            return FALSE;
        } //End of function "print_buffer"
        
        /**
         * Print file, this adds the file to the buffer and prints
         * 
         * @param string $file Filename to print
         * @return boolean
         */
        public function print_file($file) {
            /* Get file contents  */
            $fh = fopen($file, "rb"); 
            $content = fread($fh, filesize($file)); 
            fclose($fh); 
            
            $connect = $this->_connect();
            if ($connect <> FALSE) {
                // Send File to printer
                $this->write($content); 
                $this->_write_buffer();
                $this->_close();
                return TRUE;
            }
        } //End of function "print_file"
        
        /**
         * Open Printer Connection and set preferences
         * 
         * @return boolean|resource 
         */
        private function _connect() {
            //Check if the printer is already open, if it is, return the handle
            if ($this->handle <> FALSE) { return $this->handle; }
            
            /* Open the Printer Connetion */
            $this->handle = printer_open($this->selected);
            if ($this->handle == FALSE) {
                return FALSE;
            }
            
            /* Set Copies */
            $this->option(PRINTER_COPIES, $this->copies());
            
            /* Set Orientation */
            if ($this->orientation() == 'Landscape') {
                $this->option(PRINTER_ORIENTATION, PRINTER_ORIENTATION_LANDSCAPE);
            } else {
                $this->option(PRINTER_ORIENTATION, PRINTER_ORIENTATION_PORTRAIT);
            }
            
            /* Set Title */
            if ($this->document_title() <> '') {
                $this->option(PRINTER_TITLE, $this->document_title());
            }
            
            return $this->handle;
        } //End of function "_connect"
        
        /**
         * Set printer option, printer must be open for this to work
         * 
         * @param integer $option Option to Set
         * @param integer $value Value to give
         * @return boolean 
         */
        public function option($option, $value) {
            return printer_set_option($this->handle, $option, $value);
        } //End of function "option"
        
        /**
         * Write buffer contents to Printer
         * 
         * @return void 
         */
        private function _write_buffer() {
            printer_write($this->handle, $this->buffer);
            return;
        } //End of function "_write_buffer"
        
        /**
         * Close printer connection
         * 
         * @return boolean 
         */
        private function _close() {
            printer_close($this->handle);
            $this->handle = FALSE;
            return TRUE;
        } //End of function "_close"
        
    } //End of Class "Printer"
?>
