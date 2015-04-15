<?php
/**
 * Mysqli database connector
 *
 * *********************************
 * TODO:
 *      Make Insert, Update, Delete, InsertUpdate, Etc check to make sure columns and tables exist first
 **/

/**
 * Singleton. Use Database->GetDatabase
 **/
class Database {

	/**
	* Holds whether an instance exists already or not.
	*
	* @var Boolean
	*/
	private static $Exists = FALSE;

	/**
	 * The mysqli connection
	 *
	 * @var mysqli
	 */
	private $mConnection;

        /**
	 * @var Array - Holds strings of all queries made, both successful and not
	 */
	private $mQueries;
	/**
	 * @var Integer - Total number of queries executed
	 */
	private $mNumQueries;
	/**
	 * @var Integer - Total number of failed queries
	 */
	private $mNumFailedQueries;

        /*
         * Private constructor
         *
         * Creates connections
         */
        private function __construct() {
                global $dblocation, $dbuser, $dbpass, $dbname;

                $this->mConnection = new mysqli($dblocation, $dbuser, $dbpass, $dbname);

                // Setup some defaults
                $this->mQueries = array();
                $this->mNumQueries = 0;
                $this->mNumFailedQueries = 0;
                $this->mErrors = array();
        }

        /**
         * Returns the database connection
         *
         * This object is a singleton so the returned object is always
         * the same connection
         **/
	public function getDatabase() {

		if(!self::$Exists) {
			self::$Exists = new Database();
		}

		return self::$Exists;
	}

	public function query($Sql) {
	        // Perform some statistics
	        $this->incQueryCount();
	        $this->addQuery($Sql);

	        return $this->mConnection->query($Sql);
	}


        /*********************************************************
        **** PUBLIC VERSIONS OF INTERNAL FUNCTIONS
        *********************************************************/

        /**
         * Displays all run queries
         **/
         public function showQueries() {
                new dBug($this->mQueries);
         }
        /*********************************************************
        **** INTERNAL DATABASE FUNCTIONS
        *********************************************************/

        /**
         * Increment total number of queries run
         */
        private function incQueryCount() {
                $this->mNumQueries++;
        }

        /**
         * Return number of queries run on page
         *
         * @return Integer
         **/
         public function getQueryCount() {
                return $this->mNumQueries;
         }

         /**
          * Make a string safe for the database
          *
          * @return String
          **/
         public function escapeString($String) {
                return $this->mConnection->real_escape_string($String);
         }

        /**
         * Add query to keep track of all queries run
         * If the query had an error mark in here
         *
         * @param String $Sql
         * @param Boolean $Error - default false
         */
         private function addQuery($Sql, $Error = false) {
                $success = '<font color="green">';
                $failed = '<font color="red">';
                $close = '</font>';

                $s = ($Error)? $failed : $success;

                $s .= $Sql . $close;

                $this->mQueries[] = $s;
         }


		public function __toString() {
			$ret = "<strong>Num queries:</strong> " . $this->getQueryCount() . "<ol>";
			foreach($this->mQueries AS $q) {
				$ret .= "<li>$q</li>";
			}
			$ret .= "</ol>";
			return $ret;
		}

} // end class
