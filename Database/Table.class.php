<?php
/**
 * This class is useful for dealing with a single database table
 * in an abstract sort of way. It is also a great idea to extend
 * this class to deal with complex tables.
 **/

class Table {

        /**
         * Name of the table
         *
         * @var String
         **/
        private $mTable;

        /**
         * Database connection
         **/
         private $mConnection;

        public function __construct($Table) {
               // global $Db;
               // $this->mConnection = &$Db;

                $this->mTable = $Table;
        }


        /**
	 * Inserts a new row into the specified table
	 *
	 * The data passed in may be of mixed types.
	 * If a new type is desired simply add it.
	 *
	 * Example:
         * $Db->Insert('sometable', array('col1'=>'data', 'col2'=>'moredata', 'col3'=>'evenmoredata'));
         *
         * @param Mixed $Data
         * @return Boolean - success
         **/
        public function Insert($Data) {
                $ret = false;
                $query = "INSERT INTO `{$this->mTable}` SET ";

                /*
                 * Determine what type the data is and pass it to the appropriate handler
                 * The handler should return a string to be used in the query
                 */
                if(is_array($Data)) {
                        $query .= $this->BuildQueryFromArray($Data);
                } else {
                        // Could not figure out how to handle the data
                        return false;
                }

                $query .= ", Modified=NOW(), Created=NOW()";
                $ret = $this->Query($query);

                return $ret;
        }

                /**
         * Updates a row, or rows, in the specified table
         *
         * Data passed in may be of mixed types.
         * If a new type is desired simply add it.
         *
         * Example:
         * $Db->Update('anothertable', array('col1'=>'data', 'col2'=>'moredata', 'col3'=>'evenmoredata'), "this='that'");
         * $Db->Update('anothertable', array('col1'=>'data', 'col2'=>'moredata', 'col3'=>'evenmoredata'), array('this'=>'that'));
         *
         * @param Mixed $Data
         * @param Mixed $Where
         * @return Boolean - success
         **/
         public function Update($Data, $Where) {
                $ret = false;
                $query = "UPDATE `{$this->mTable}` SET ";

                /*
                 * Determine what type the data is and pass it to the appropriate handler
                 * The handler should return a string to be used in the query
                 */
                if(is_array($Data)) {
                        $query .= $this->BuildQueryFromArray($Data);
                } else {
                        // Could not figure out how to handle the data
                        $ret = false;
                }

                $query .= ", Modified=NOW() WHERE ";

                /*
                 * Determine what type the data is and pass it to the appropriate handler
                 * The handler should return a string to be used in the query
                 */
                if(is_array($Where)) {
                        $query .= $this->BuildQueryFromArray($Where);
                } else {
                        // Could not figure it out. Must be a string
                        $query .= $Where;
                }

                $ret = $this->Query($query);

                return $ret;
         }

         /**
          * Deletes a row, or rows, from the specified table
          *
          * NOTE: IF YOU DO NOT PASS A WHERE THE ENTIRE TABLE WILL BE ERASED!!!
          *
          * Example:
          * $Db->Delete('sometable', "this='that');
          * $Db->Delete('sometable', array('this'=>'that'));
          *
          * @param Mixed $Where
          * @return Boolean - success
          **/
          public function Delete($Where) {
                $ret = false;
                $query = "DELETE FROM `{$this->mTable}` ";

                /*
                 * Determine what type the data is and pass it to the appropriate handler
                 * The handler should return a string to be used in the query
                 */
                if(is_array($Where)) {
                        $query .= $this->BuildQueryFromArray($Where);
                } else {
                        // Must be a string
                        $query .= $Where;
                }

                $ret = $this->Query($query);

                return $ret;
        }

        /**
         * Used to create a custom query. Calls the Database query method.
         * $Db must be available as a global variable
         *
         * @param String $Query
         * @param Boolean
         **/
         public function Query($Query) {
                global $Db;
                return $Db->Query($Query);
         }

         public function EscapeString($S) {
                global $Db;
                return $Db->EscapeString($S);
         }

        /**
         * Given an array this function returns part of an sql query
         *
         * @param Array $Data
         * @return String
         **/
        private function BuildQueryFromArray($Data) {
                $a = array();
                if(!is_array($Data)) return $a; // Assure data is an array

                foreach($Data as $k => $v) {
                        $v = $this->EscapeString($v);
                        $a[] = "$k='$v'";
                }
                $a = implode(',', $a);

                return $a;
        }

} // end Class
