<?php
session_start();
$id=$_GET['id'];
$i=0;	$j=0;
$spage=1;	
if(empty($searchcount))
	$searchcount=0;
function GetBetween($var1="",$var2="",$pool)
{
    $temp1 = strpos($pool,$var1)+strlen($var1);
    $result = substr($pool,$temp1,strlen($pool));
    $dd=strpos($result,$var2);
    if($dd == 0){
        $dd = strlen($result);
    }
	return substr($result,0,$dd);
}
if(!empty($id))
{
	$i=0;
	$content = file_get_contents("https://www.omdbapi.com/?i=$id&apikey=9e4679f5");
	$info=GetBetween("{",",\"Ratings",$content);
	$info=str_replace("\":\"","\"=>\"",$info);
	ob_start();
	echo "Array(".$info.")";
	$info = ob_get_clean();
	$info=eval("return $info;");
	
	$ratings=GetBetween("[","]",$content);
	$ratings=str_replace("{",null,$ratings);
	$ratings=str_replace("{",null,$ratings);
	$ratings=str_replace("},","#",$ratings);
	$ratings=str_replace("\":\"","\"=>\"",$ratings);
	$ratings=str_replace("}","#",$ratings);
	$ratings=explode("#",$ratings);
	
	$info2=GetBetween("],","}",$content);
	$info2=str_replace("\":\"","\"=>\"",$info2);
	ob_start();
	echo "Array(".$info2.")";
	$tmp= ob_get_clean();
	$tmp=eval("return $tmp;");
	$info2=$tmp;
}

?>

<!DOCTYPE html>
<html>
<head>
	<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>OMDB API</title>
	<link rel="stylesheet" type="text/css" href="style.css"/>
	<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
</head>
<style>
body,h1 {font-family: "Raleway", sans-serif}
body, html {height: 100%}
.bgimg {
    background-image: url('files/bg.jpg');
    min-height: 100%;
    background-position: center;
    background-size: cover;
}

#main_search {
    float: right;
    line-height: 60px;
    margin: 0 50px 0 0;
}
#main_search_input {
    border-top-left-radius: 5px;
    border-bottom-left-radius: 50px;
    height: 20px;
    line-height: 20px;
    width: 200px;
    vertical-align: middle;
    padding: 5px 10px 5px 10px !important;
}
#search_button {
    background-color: #FFF;
    border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;
    height: 30px;
    vertical-align: top;
}
input, button {
    margin:0;
}

* {
    box-sizing: border-box;
}
p{
	display:inline;
}
body {
    margin: 0;
}
table,td{
	border:10px solid transparent;
	border-collapse: separate;
	border-spacing: 15px;
}
table{
width:100%;
margin-left:auto;
margin-right:auto;
}
img{
	border:5px solid black;
	border-radius:15px;
}
a:link {
    color: white;
}
a:visited {
    color: white;
}
 a:hover {
    color: white;
}
a:active {
     color: white;
}
</style>
<body>
<div class="bgimg w3-display-container w3-animate-opacity w3-text-white">
<div class="fixed-action-btn horizontal w3-display-topright">
	<form method="get" action="search.php">
		<input id="main_search_input" type="text" name="search" placeholder="Search" />
		<button id="search_button" type="submit" class="btn btn-small blue darken" name="search_btn"><i class="material-icons">search</i></button>
	</form>
</div>
<div style="font-size:45px;text-align:center;" class="blue-grey-text text-lighten-4"><b>OMDb Database</b></div>
<table style="width:100%;">
<tr>
<th><img style="margin-left:20px" src="<?php if($info['Poster']=="N/A") {$info['Poster']='files/default.jpg';} echo $info["Poster"];?>" width="300px" height="445px"></th>
<td width='60%' style="vertical-align:top;"><h2 style="font-size:35px;"><b><?php echo "".$info["Title"]." (".$info["Year"].")";?></b></h2><?php echo "".$info["Released"]." | ".$info["Genre"]." | ".$info["Runtime"]." | ".$info["Rated"]."";?><h4><p style="font-size:14px;"><b>Director: </b><?php echo $info["Director"];?><br><text class="item"><b>Writer: </b><?php echo $info["Writer"];?></text><br><b>Stars: </b><?php echo $info["Actors"];?><br><b>Production: </b><?php echo $info2["Production"];?><br><b>Languages: </b><?php echo $info["Language"];?><br><b>Country: </b><?php echo $info["Country"];?><br><b>Plot: </b><?php echo $info["Plot"];?><br><b>Awards: </b><?php echo $info["Awards"];?></p></h3><b>Ratings: <br><h4><p style="font-size:14px;">IMDb: <?php echo "".$info2["imdbRating"]."/10 (".$info2["imdbVotes"]." votes)";?><br></b>
<?php
while($ratings[$i+1])
{
	ob_start();
	echo "Array(".$ratings[$i+1].")";
	$tmp = ob_get_clean();
	$data=eval("return $tmp;");
	echo "<b>".$data["Source"]."</b>: ".$data["Value"]."<br>";
	$i++;
}
?>
</p></h4>
<b>Website: </b><?php if($info2["Website"]!="N/A" && !empty($info2["Website"])) echo "<a href=".$info2["Website"]." target='_blank'>".$info2["Website"]."</a>"; else echo "Not Available";?>
</td>
<td width='25%' style="text-align:center;"><h2 style="font-size:20px;">
<b>Similar Movies</b></h2><br>
<?php

$similar=$_SESSION['first'];
while($spage<2 && $searchcount<4)
{
	$j=0;
	$scontent = file_get_contents("https://www.omdbapi.com/?s=$similar&apikey=9e4679f5&page=$spage");
	$scontent=str_replace("{",null,$scontent);
	$scontent=str_replace("},","#",$scontent);
	$scontent=str_replace("\":\"","\"=>\"",$scontent);
	$scontent=str_replace("}","#",$scontent);
	$scontent=GetBetween("[","]",$scontent);
	$scontent=str_replace("Response","\"Response",$scontent);
	$smovie=explode("#",$scontent);
	ob_start();
	echo "Array(".$smovie[0].")";
	$tmp = ob_get_clean();
	$tmp=eval("return $tmp;");
	$response=$tmp;
	if(empty($response) || empty($response["Response"]))
			$response["Response"]="True";
	while(!empty($smovie[$j]) && $searchcount<4)
	{
		ob_start();
		echo "Array(".$smovie[$j].")";
		$tmp = ob_get_clean();
		$tmp=eval("return $tmp;");
		$tmovie=$tmp;
		if(!empty($tmovie['imdbID']) && $searchcount<4)
		{
			$imdbid=$tmovie['imdbID'];
			$icontent = file_get_contents("https://www.omdbapi.com/?i=$imdbid&apikey=9e4679f5");
			$icontent=GetBetween("{",",\"Ratings",$icontent);
			$icontent=str_replace("\":\"","\"=>\"",$icontent);
			ob_start();
			echo "Array(".$icontent.")";
			$icontent = ob_get_clean();
			$icontent=eval("return $icontent;");
			$idata=$icontent;
			$idataarr=explode(", ",$idata['Genre']);
			$iinfoarr=explode(", ",$info['Genre']);
			if(array_intersect($idataarr,$iinfoarr) && $idata['Title']!=$info['Title'])
			{
				echo "&nbsp&nbsp<a href='movie.php?id=$imdbid' title='".$idata['Title']."'><img src=".$idata['Poster']." width='110px' height='163px'></a>&nbsp&nbsp";
				$searchcount++;
				if($searchcount%2==0)
					echo "<br><br>";
			}
		}
		$j++;
	}
	if($response['Response']=="False")
		break;
	if($searchcount==0)
		echo "<h6>No Similar Movies Found<h6>";
	$spage++;
}

?>
</td>
</tr>
</table> 
</div>
</body>
<script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="js/materialize.min.js"></script>
<script type="text/javascript" src="js/readmore.js"></script>
</html>