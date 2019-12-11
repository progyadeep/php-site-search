<?php
	$q_words = explode(",", $_GET['q']); //holds supplied keywords
	$ignore_list = explode("\n", file_get_contents('ignore')); //holds files/folders to ignore in search
	$score_tab = []; //will hold the search scores of files found on website
	$sub = []; //will hold list of folders found on the website
	
	//initial list of files/folders to include in search scope
	$search_index = array_diff(scandir(getcwd()), [".", ".."], $ignore_list);
	
	//finding out folders in root directory
	//the goal is to have only 'files' in $search_index
	$l = count($search_index);
	for($i=0; $i<$l; $i++){
		$s = $search_index[$i];
		if(is_dir($s)){
			array_push($sub, $s);
			unset($search_index[$i]);
		}
	}
	
	//finding all "files" in all folders/sub-folders across the site
	//and calculating their search score

	for($i=0; count($sub)>0; $i++){
		$s = $sub[$i];
		$t_s = array_diff(scandir($s), [".", ".."]);
		foreach($t_s as $t){
			if(!in_array($s."/".$t, $ignore_list)){
				if(is_dir($s."/".$t))
					array_push($sub, $s."/".$t); //it's a folder
				else
					$score = calcScore($s."/".$t); //it's a file!
			}
		}
		unset($sub[$i]);
	}
		
	//calulating score of initial $search_index elements
	foreach($search_index as $s)
		calcScore($s);

	//sorting score table in descending order of scores
	arsort($score_tab);
	
	//giving results as JSON array
	echo str_replace("\\", "", json_encode($score_tab));

	function calcScore($f){
		global $q_words;
		global $score_tab;
		$raw_c = file_get_contents($f);
		$si = strpos($raw_c, "<meta name=\"keywords\" content=\"")+31;
		$kw = preg_split("/(,\s*+)/", substr($raw_c, $si, strpos($raw_c, "\"/>", $si)-$si));
		
		$score = 0;
			foreach($q_words as $q){
			if(in_array($q, $kw)){
				$score++;
			}
		}
		
		if($score > 0)
			$score_tab[$f] = $score;
	}
?>
