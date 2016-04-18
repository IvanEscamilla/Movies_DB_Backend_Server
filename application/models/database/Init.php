<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Init extends CI_Model {

    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        //Connect to database
        $this->load->database();
    }
    
    public function generateCsv($data, $delimiter = ',') 
    {
        $temp = "";
        for($i = 0; $i < count($data)-1; $i++) 
        {
            $temp .= " ".$data[$i] . $delimiter; 
        }

        $temp .= " ".$data[count($data)- 1];

        return $temp;
    }

    public function insert_movie_data($movie)
    {   
        /*actors data*/
        $actors = array();
        $actors = explode(', ', $movie->Actors);
        $actorIdArr = array();
        /*get actors ids*/
        foreach ($actors as $actor) 
        {
            $this->db->select('id, name');
            $this->db->where('name',$actor);
            $result = $this->db->get('actors_data')->row();
            
            /*If actor was found*/
            if($result != NULL)
            {
                /*get id*/
                $actorId = $result->id;
            }
            else
            {
                $this->db->set("name",$actor);
                $this->db->insert("actors_data");
                $actorId = $this->db->insert_id();
            }
            
            array_push($actorIdArr, $actorId);
        }
        $actorsIDs = $this->generateCsv($actorIdArr); 
        /*get directors id*/
        /*directors data*/
        $directors = array();
        $directors = explode(', ', $movie->Director);
        $directorIdArr = array();
        foreach ($directors as $director) 
        {
            $this->db->select('id, name');
            $this->db->where('name',$director);
            $result = $this->db->get('directors_data')->row();
            
            /*If director was found*/
            if($result != NULL)
            {
                /*get id*/
                $directorId = $result->id;
            }
            else
            {
                $this->db->set("name",$director);
                $this->db->insert("directors_data");
                $directorId = $this->db->insert_id();
            }
            
            array_push($directorIdArr, $directorId);
        }
        $directorsIDs = $this->generateCsv($directorIdArr);
        /*get writers id*/
        /*writers data*/
        $writers = array();
        $writers = explode(', ', $movie->Writer);
        $writersIdArr = array();
        foreach ($writers as $writer) 
        {
            $this->db->select('id, name');
            $this->db->where('name',$writer);
            $result = $this->db->get('writers_data')->row();
            
            /*If writer was found*/
            if($result != NULL)
            {
                /*get id*/
                $writerId = $result->id;
            }
            else
            {
                $this->db->set("name",$writer);
                $this->db->insert("writers_data");
                $writerId = $this->db->insert_id();
            }
            
            array_push($writersIdArr, $writerId);
        }
        $writersIDs = $this->generateCsv($writersIdArr);
        /*Genre data*/
        $genders = array();
        $genders = explode(', ', $movie->Genre);
        $gendersIdArr = array();
        foreach ($genders as $gender) 
        {
            $this->db->select('id, gender');
            $this->db->where('gender',$gender);
            $result = $this->db->get('gender_data')->row();
            
            /*If gender was found*/
            if($result != NULL)
            {
                /*get id*/
                $genderId = $result->id;
            }
            else
            {
                $this->db->set("gender",$gender);
                $this->db->insert("gender_data");
                $genderId = $this->db->insert_id();
            }
            
            array_push($gendersIdArr, $genderId);
        }
        $genereIDs = $this->generateCsv($gendersIdArr);
        /*country_data*/
        $countrys = array();
        $countrys = explode(', ', $movie->Country);
        $countrysIdArr = array();
        foreach ($countrys as $country) 
        {
            $this->db->select('id, country');
            $this->db->where('country',$country);
            $result = $this->db->get('country_data')->row();
            
            /*If country was found*/
            if($result != NULL)
            {
                /*get id*/
                $countryId = $result->id;
            }
            else
            {
                $this->db->set("country",$country);
                $this->db->insert("country_data");
                $countryId = $this->db->insert_id();
            }
            
            array_push($countrysIdArr, $countryId);
        }
        $countryIDs = $this->generateCsv($countrysIdArr);
        /*Language data*/
        $languages = array();
        $languages = explode(', ', $movie->Language);
        $languagesIdArr = array();
        foreach ($languages as $language) 
        {
            $this->db->select('id, language');
            $this->db->where('language',$language);
            $result = $this->db->get('languages_data')->row();
            
            /*If language was found*/
            if($result != NULL)
            {
                /*get id*/
                $languageId = $result->id;
            }
            else
            {
                $this->db->set("language",$language);
                $this->db->insert("languages_data");
                $languageId = $this->db->insert_id();
            }
            
            array_push($languagesIdArr, $languageId);
        }
        $langugesIDs = $this->generateCsv($languagesIdArr);

        /*type data*/
        $types = array();
        $types = explode(', ', $movie->Type);
        $typesIdArr = array();
        foreach ($types as $type) 
        {
            $this->db->select('id, type');
            $this->db->where('type',$type);
            $result = $this->db->get('types_data')->row();
            
            /*If type was found*/
            if($result != NULL)
            {
                /*get id*/
                $typeId = $result->id;
            }
            else
            {
                $this->db->set("type",$type);
                $this->db->insert("types_data");
                $typeId = $this->db->insert_id();
            }
            
            array_push($typesIdArr, $typeId);
        }
        $typesIDs = $this->generateCsv($typesIdArr);

        /*now insert movie data and get its id*/
        $this->db->select('id');
        $this->db->where('title',$movie->Title);
        $result = $this->db->get('movies_data')->row();
        
        /*If imdb was found*/
        if($result != NULL)
        {
            /*get id*/
            $movieID = $result->id;
        }
        else
        {
            $this->db->set("title",$movie->Title);
            $this->db->set("year",$movie->Year);
            $this->db->set("duration",$movie->Runtime);
            $this->db->set("plot",$movie->Plot);
            $this->db->set("typesID",$typesIDs);
            $this->db->set("posterUrl",$movie->Poster);
            $this->db->set("awards",$movie->Awards);
            $this->db->set("countryID",$countryIDs);
            $this->db->set("languageID",$langugesIDs);
            $this->db->set("actorsIDs",$actorsIDs);
            $this->db->set("directorsIDs",$directorsIDs);
            $this->db->set("writersIDs",$writersIDs);
           /* $this->db->set("imdbDataID",$movie->);*/
            $this->db->set("genereID",$genereIDs);
            $this->db->insert("movies_data");
            $movieID = $this->db->insert_id();
        }

        /*imdb data last inserted*/
        $this->db->select('id');
        $this->db->where('movieID',$movieID);
        $result = $this->db->get('imdb_data')->row();
        
        /*If imdb was found*/
        if($result != NULL)
        {
            /*get id*/
            $this->db->set("imdbRating",$movie->imdbRating);
            $this->db->set("imdbVotes",$movie->imdbVotes);
            $this->db->set("imdbID",$movie->imdbID);
            $this->db->where("id",$result->id);
            $this->db->update("imdb_data");
        }
        else
        {
            $this->db->set("imdbRating",$movie->imdbRating);
            $this->db->set("imdbVotes",$movie->imdbVotes);
            $this->db->set("imdbID",$movie->imdbID);
            $this->db->set("movieID",$movieID);
            $this->db->insert("imdb_data");
            $imdbID = $this->db->insert_id();

            $this->db->set("imdbDataID",$imdbID);
            $this->db->where("id",$movieID);
            $this->db->update("movies_data");
        }
        return "OK";
    }



}