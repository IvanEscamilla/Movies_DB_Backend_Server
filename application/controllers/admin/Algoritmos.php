<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$relations = [];

class Algoritmos extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function relationExists($a,$b,$relationType)
	{
		if($a == $b)
			return $relationType?1:0;
		
		global $relations;
		$relationsToCheck = [$a];
		$relationTypes = [$relationType];

		for($i=0;$i<count($relationsToCheck);$i++)
		{	
			
			if(isset($relations[$relationsToCheck[$i]][$b]))
			{
				if($relations[$relationsToCheck[$i]][$b] === $relationTypes[$i])
					return 1;
				else if($relations[$relationsToCheck[$i]][$b] === !$relationTypes[$i])
					return 0;
			}

			foreach ($relations[$relationsToCheck[$i]] as $j =>$relation) 
			{

				if(!in_array($j,$relationsToCheck))
				{
					array_push($relationsToCheck, $j);
					array_push($relationTypes, !($relation ^ $relationTypes[$i]));
				}

			}	
			
		}
		return 0;
	}

	public function setFriends($a,$b)
	{
		$areEnemies = $this->areEnemies($a,$b);
		if($areEnemies)
		{
			return -1;
		}
		
		global $relations;
		$relations[$a][$b] = true;
		$relations[$b][$a] = true;
		return NULL;
	}

	public function setEnemies($a,$b)
	{
		$areFriends = $this->areFriends($a,$b);
		if($areFriends)
		{
			return -1;
		}
		
		global $relations;
		$relations[$a][$b] = false;
		$relations[$b][$a] = false;
		return NULL;	
	}

	public function areFriends($a,$b)
	{
		return $this->relationExists($a,$b,true);
	}

	public function areEnemies($a,$b)
	{
		return $this->relationExists($a,$b,false);
	}

	public function start_wargame()
	{
		//Load http request raw data and decoded params to class
        $this->httpData = $this->input->get_post('data');

        if((!is_string($this->httpData)) && ((is_array($this->httpData)) || (is_object($this->httpData))))
        {
            $this->httpParams = json_decode(json_encode($this->httpData));
        }
        else
        {
            $this->httpParams = json_decode($this->httpData);
        }
        
		$data = $this->httpParams->data;

		/*war game algoritm*/
		$input = explode("\n",$data);
		
		$n = $input[0];

		/*$test = array_fill(0, $n, []);*/
		
		global $relations;
		$relations = array_fill(0, $n, []);
		
		$functions = ["setFriends","setEnemies","areFriends","areEnemies"];
		$orders = [];
		for($i = 1; $i < (count($input)-1) ; $i++)
		{
			array_push($orders, explode(" ",$input[$i]));
		}
		
		$resultGame  = "";
		
		foreach ($orders as $order) 
		{	
			$ans = $this->$functions[$order[0]-1]($order[1],$order[2]);
			if($ans !== NULL)
			{
				$resultGame .= $ans;
				$resultGame .="\n";
			}
		}
		/*print_r($resultGame);*/
		$response["endWar"] = $resultGame;
		$response["status"] = "OK";
		
		die(json_encode(json_decode(json_encode($response), true)));
	}

}
