<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Get extends CI_Model {

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
        for($i = 0; $i < count($data); $i++) 
        {
            if($i == (count($data)-1))
            {
                $temp .= " ".$data[$i]; 
            }
            else
            {
                $temp .= " ".$data[$i] . $delimiter; 
            }
        }

        return $temp;
    }

    public function get_movie_data($busqueda)
    {   
        /*first know if is a search of actor, directo or writer movies*/
        $sql = "SELECT id, name, 'actor' as type FROM movies_db.actors_data where name like ?UNION
                SELECT id, name, 'director' as type FROM movies_db.directors_data where name like ?UNION
                SELECT id, name, 'writer' as type FROM movies_db.writers_data where name like ?UNION
                SELECT id, title, 'title' as type FROM movies_db.movies_data where title like ?";

        $result = $this->db->query($sql,array($busqueda,$busqueda,$busqueda,$busqueda))->result();
        
        $actors = array();
        $actorsIndex = 0;
        $directors = array();
        $directorsIndex = 0;
        $writers = array();
        $writersIndex = 0;
        $titles  = array();
        $titlesIndex = 0;

        foreach ($result as $user)
        {
            switch ($user->type) 
            {
                case 'actor':
                    $actors[$actorsIndex] = $user->id;
                    $actorsIndex++;
                    break;
                
                case 'director':
                    $directors[$directorsIndex] = $user->id;
                    $directorsIndex++;
                    break;
                
                case 'writer':
                    $writers[$writersIndex] = $user->id;
                    $writersIndex++;
                    break;
                
                case 'title':
                    $titles[$titlesIndex] = $user->id;
                    $titlesIndex++;
                    break;
                
                default:
                    break;
            }
        }

        $movies = array();
        $moviesIndex=0;
        /*validate if array is empty*/
        if(count($actors))
        {
            foreach ($actors as $actor)
            {
                $sql = "SELECT id FROM movies_db.movies_data where actorsIDs like '% ".$actor."%';";
                $result = $this->db->query($sql)->result();
                if(count($result))
                {
                    foreach ($result as $movieFound) 
                    {
                        $movies[$moviesIndex] = $movieFound->id;
                        $moviesIndex++;
                    }
                }
            }
           
        }

        if(count($directors))
        {
            foreach ($directors as $director)
            {
                $sql = "SELECT id FROM movies_db.movies_data where directorsIDs like '% ".$director."%';";
                $result = $this->db->query($sql)->result();
                if(count($result))
                {
                    foreach ($result as $movieFound) 
                    {
                        $movies[$moviesIndex] = $movieFound->id;
                        $moviesIndex++;
                    }
                }
            }
        }

        if(count($writers))
        {
            foreach ($writers as $writer)
            {
                $sql = "SELECT id FROM movies_db.movies_data where writersIDs like '% ".$writer."%';";
                $result = $this->db->query($sql)->result();
                if(count($result))
                {
                    foreach ($result as $movieFound) 
                    {
                        $movies[$moviesIndex] = $movieFound->id;
                        $moviesIndex++;
                    }
                }
            }
        }

        if(count($titles))
        {
            foreach ($titles as $title)
            {
                $movies[$moviesIndex] = $title;
                $moviesIndex++;
            }
        }

        $uniqueIDs = array_values(array_unique($movies));
        
        /*Get movie data*/
        $result = array();
        if(count($uniqueIDs))
        {
            $sql = "SELECT * FROM movies_db.movies_data where id in (".$this->generateCsv($uniqueIDs).");";
            $result = $this->db->query($sql)->result();

            foreach ($result as $movie) 
            {
                $sql = "SELECT type FROM movies_db.types_data where id in (".$movie->typesID.");";
                $types = $this->db->query($sql)->result();
                unset($movie->typesID);
                $movie->types = $types;

                $sql = "SELECT country FROM movies_db.country_data where id in (".$movie->countryID.");";
                $country = $this->db->query($sql)->result();
                unset($movie->countryID);
                $movie->countries = $country;
                
                $sql = "SELECT language FROM movies_db.languages_data where id in (".$movie->languageID.");";
                $language = $this->db->query($sql)->result();
                unset($movie->languageID);
                $movie->languages = $language;

                $sql = "SELECT imdbVotes, imdbRating FROM movies_db.imdb_data where id = ".$movie->id.";";
                $imdbData = $this->db->query($sql)->result();
                $movie->imdb = $imdbData;
                
                $sql = "SELECT gender FROM movies_db.gender_data where id in (".$movie->genereID.");";
                $gender = $this->db->query($sql)->result();
                unset($movie->genereID);
                $movie->generes = $gender;
                
                $sql = "SELECT name, imgUrl FROM movies_db.actors_data where id in (".$movie->actorsIDs.");";
                $actors = $this->db->query($sql)->result();
                unset($movie->actorsIDs);
                $movie->actors = $actors;
                
                $sql = "SELECT name, imgUrl FROM movies_db.actors_data where id in (".$movie->directorsIDs.");";
                $directors = $this->db->query($sql)->result();
                unset($movie->directorsIDs);
                $movie->directors = $directors;
                
                $sql = "SELECT name, imgUrl FROM movies_db.writers_data where id in (".$movie->writersIDs.");";
                $writers = $this->db->query($sql)->result();
                unset($movie->writersIDs);
                $movie->writers = $writers;
            }
        }
        
        return $result;
    }



}