<?php
	$q_words = explode(",", $_GET['q']); //holds query keywords
	$ignore_list = explode("\n", file_get_contents('ignore')); //holds files/folders to ignore
	$sub = []; //holds folders/subfolders to expand
	$score_tab = []; //score table
	//initial list of files/folders to include in search scope
	$search_index = array_diff(scandir(getcwd()), [".", ".."], $ignore_list);
	
	//finding out folders in root directory
	//the goal is to have only files in $search_index
	foreach($search_index as $s){
		if(is_dir($s))
			array_push($sub, $s);
	}
	
	$search_index = array_diff($search_index, $sub);
	
	//finding all "files" in all folders/sub-folders across the site
	//and calculating their search score
	for($i=0; count($sub)>0; $i++){
		$s = $sub[$i];
		$t_s = array_diff(scandir($s), [".", ".."]);
		foreach($t_s as $t){
			if(!in_array($s."/".$t, $ignore_list)){
				if(is_dir($s."/".$t))
					array_push($sub, $s."/".$t);
				else
					array_push($search_index, $s."/".$t);
			}
		}
		unset($sub[$i]);
	}
	
	//calculating score of search index
	foreach($search_index as $s){
		$raw_c = file_get_contents($s);
		$si = strpos($raw_c, "<meta name=\"keywords\" content=\"")+31;
		$kw = preg_split("/(,\s*+)/", substr($raw_c, $si, strpos($raw_c, "\"/>", $si)-$si));
		
		$score = 0;
		foreach($q_words as $q){
			foreach($kw as $k){
				if(strpos($k, $q) !== false)
					$score++;
			}
		}
		if($score > 0)
			$score_tab[$s] = $score;
	}
	
	//sorting score table in descending order of scores
	arsort($score_tab);
	
	//giving results as JSON array
	$res = str_replace("\\", "", json_encode($score_tab));
	if($res == "[]")
		echo "{}";
	else
		echo $res;
		
	/*------------------------------------------------------*
	*							*
	* 		END OF MAIN PROGRAM FLOW		*
	* 							*
	--------------------------------------------------------*/
?>
