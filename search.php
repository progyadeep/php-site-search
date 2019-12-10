<?php
	$q_words = explode(",", $_GET['q']);
	$ignore_list = explode("\n", file_get_contents('ignore'));
	
	//initial list of files/folders to include in search scope
	$search_index = array_diff(scandir(getcwd()), [".", ".."]);
	$search_index = array_diff($search_index, $ignore_list);
	
	//finding out sub-folders in root directory
	$sub = [];
	
	foreach($search_index as $s){
		if(is_dir($s))
			array_push($sub, $s);
	}
	
	$search_index = array_diff($search_index, $sub);
	
	//finding all "files" in all sub-folders across the site
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
	//print_r($search_index)."\n";
	
	//Search index built, now will read meta keywords and calculate
	//search score
	$score_tab = [];
	
	foreach($search_index as $s){
		$raw_c = file_get_contents($s);
		$si = strpos($raw_c, "<meta name=\"keywords\" content=\"")+31;
		$kw = preg_split("/(,\s*+)/", substr($raw_c, $si, strpos($raw_c, "\"/>", $si)-$si));
		
		$score = 0;
			foreach($q_words as $q){
			if(in_array($q, $kw)){
					$score++;
			}
		}
		if($score > 0)
			$score_tab[$s] = $score;
	}
	arsort($score_tab);
	
	//giving results as JSON array
	echo str_replace("\\", "", json_encode($score_tab));
?>
