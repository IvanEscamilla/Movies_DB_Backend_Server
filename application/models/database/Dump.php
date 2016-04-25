<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dump extends CI_Model {

    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        //Connect to database
        $this->load->database();
        
    }

    public function dump_all()
    {   
        $tables = array("actors_data",
                  "country_data",
                  "directors_data",
                  "gender_data",
                  "imdb_data",
                  "languages_data",
                  "movies_data",
                  "types_data",
                  "writers_data");
        $movies_dump = (object)[];
        $movies_dump->database_schema = "movies_db";
        $movies_dump->tables = [];
        $index = 0;
        foreach ($tables as $table) 
        {
            $sql = "SELECT * FROM movies_db.".$table.";";
            $tempDataTable = $this->db->query($sql)->result();
            
            $sql = "SELECT COLUMN_NAME, COLUMN_TYPE ,IS_NULLABLE, EXTRA, COLUMN_KEY
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE TABLE_NAME = '".$table."';";



            $tempTableInfo = $this->db->query($sql)->result();


            $movies_dump->tables[$index] = (object)[];
            $movies_dump->tables[$index]->table_name = $table;
            $movies_dump->tables[$index]->columns_def = $tempTableInfo;
            $movies_dump->tables[$index]->data = $tempDataTable;
            $index++;
        }

        return $movies_dump;
    }
}