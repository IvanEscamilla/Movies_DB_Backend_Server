<?php 

		$n = 13;

		$relations = array_fill(0, $n,[]);

		$functions = [setFriends,setEnemies,areFriends,areEnemies];

		$orders = [
			[2, 0, 5],
			[1, 1, 7],
			[1, 8, 1],
			[1, 5, 7],
			[2, 4, 6],
			[2, 12, 7],
			[2, 9, 2],
			[1, 10, 2],
			[1, 11, 10],
			[1, 0, 10],
			[2, 6, 2],
			[1, 7, 9],
			[3, 7, 9],
			[2, 7, 6],
			[3, 4, 2],
			[4, 7, 11],
			[3, 8, 11],
			[3, 8, 5],
			[3, 9, 4],
			[4, 6, 9],
			[3, 10, 5],
			[3, 12, 11],
			[4, 10, 5],
			[4, 12, 11],
			[3, 12, 11]
		];

		// $orders = [
		// 	[1,0,1],
		// 	[1,1,2],
		// 	[2,0,5],
		// 	[3,0,2],
		// 	[3,8,9], 
		// 	[4,1,5],
		// 	[4,1,2],
		// 	[4,8,9], 
		// 	[1,8,9],
		// 	[1,5,2],
		// 	[3,5,2]
		// ];

		foreach ($orders as $order) 
		{
			echo $functions[$order[0]-1]($order[1],$order[2]);
			
			echo "\n";
		}

		function setFriends($a,$b)
		{
			$areEnemies = areEnemies($a,$b);
			if($areEnemies)
				return -1;
			
			global $relations;
			$relations[$a][$b] = true;
			$relations[$b][$a] = true;
			
		}

		function setEnemies($a,$b)
		{
			$areFriends = areFriends($a,$b);
			if($areFriends)
				return -1;
			
			global $relations;
			$relations[$a][$b] = false;
			$relations[$b][$a] = false;
			
		}

		function areFriends($a,$b)
		{
			return relationExists($a,$b,true);
		}

		function areEnemies($a,$b)
		{
			return relationExists($a,$b,false);
		}


		function relationExists($a,$b,$relationType)
		{
			if($a == $b)
				return $relationType?1:0;
			
			global $relations;
			$relationsToCheck = [$a];
			$relationTypes = [$relationType];

			for($i=0;$i<count($relationsToCheck);$i++)
			{	

				if($relations[$relationsToCheck[$i]][$b] === $relationTypes[$i])
					return 1;
				else if($relations[$relationsToCheck[$i]][$b] === !$relationTypes[$i])
					return 0;

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