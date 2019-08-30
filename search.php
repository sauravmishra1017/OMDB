<?php
session_start();
if(empty($_GLOBALS['flag']))
	$_GLOBALS['flag']=0;
if(empty($_GLOBALS['error']))
	$_GLOBALS['error']=0;
if(empty($_SESSION['page']))
	$_SESSION['page']=1;

if(isset($_GET['prev_btn']))
	--$_SESSION['page'];
else if(isset($_GET['next_btn']))
	++$_SESSION['page'];
else if(isset($_GET['jump_btn']))
{
	if($_GET['jump']>0 && $_GET['jump']<1000)
		$_SESSION['page']=$_GET['jump'];
	else
		$_GLOBALS['error']=1;
}
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
if(isset($_GET['search_btn']) || isset($_GET['prev_btn']) || isset($_GET['next_btn']) || isset($_GET["jump_btn"]))
{
	if(isset($_GET['search_btn']))
	{
		$_SESSION['search']=$_GET['search'];
		unset($_SESSION['page']);
	}
	$search=$_SESSION['search'];
	$first=explode(' ',$search);
	$_SESSION['first']=$first[0];
	$search=str_replace(" ","+",$search);
	$i=0;	$j=0;
	$k=0;	$l=0;
	$m=0;	$n=0;
	if(!empty($search))
	{
		if(empty($_SESSION['page']))
			$_SESSION['page']=1;
		$page=$_SESSION['page'];
		$content = file_get_contents("https://www.omdbapi.com/?s=$search&apikey=9e4679f5&page=$page");
		$totalresults=GetBetween("totalResults\":\"","\"",$content);
		$content=str_replace("{",null,$content);
		$content=str_replace("},","#",$content);
		$content=str_replace("\":\"","\"=>\"",$content);
		$content=str_replace("}","#",$content);
		$content=GetBetween("[","]",$content);
		$content=str_replace("Response","\"Response",$content);
		$movie=explode("#",$content);
		ob_start();
		echo "Array(".$movie[0].")";
		$tmp = ob_get_clean();
		$tmp=eval("return $tmp;");
		$response=$tmp;
		if(empty($response) || empty($response["Response"]))
			$response["Response"]="True";
	}
	else
		header("location:index.php");
}
else
	header("location:index.php");
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

body {
    margin: 0;
}
table,td{
	border:10px solid transparent;
	border-collapse: separate;
	border-spacing: 15px;
	color:black
}
table{
width:100%;
margin-left:auto;
margin-right:auto;
}
td{
	background:white;
}
img{
	width: 240px;
    height: 356px;
}
div,td{
	text-align:center;
}
a:link {
    color: black;
}
a:visited {
    color: black;
}
 a:hover {
    color: black;
}
a:active {
     color: black;
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
<div style="font-size:45px;text-align:center;" class=" blue-grey-text text-lighten-4"><b>OMDb Database</b></div>
<table style="width:1%;">
<tr>
<div style="font-size:20px;text-align:center;color:red"><?php if($_GLOBALS['error']==1) {echo "Enter a valid page between 1 and 999"; $_SESSION['page']=1;}?></div>
<p>Total Results: <?php echo $totalresults;?> (including movies, series and games)</p><br><div style="font-size:30px;text-align:center;"><?php if($response["Response"]=="True") echo "Movies";?></div>
<?php 
if(!empty($search) && $response["Response"]=="True")
{
	while($movie[$i])
	{
	ob_start();
	echo "Array(".$movie[$i].")";
	$info = ob_get_clean();
	$data=eval("return $info;");
	if($j%4==0)
		echo "</tr><tr>";
	if($data['Type']=="movie")
	{
		if($data['Poster']=="N/A")
		{
			$data['Poster']='files/default.jpg';
		}
		echo "<td><a href='movie.php?id=".$data["imdbID"]."'><b>".$data['Title']." (".$data['Year'].")</a><br/><br/><a href='movie.php?id=".$data["imdbID"]."'><img  src=".$data['Poster']." height='350px' align='center'><br/>"."</td>";
		$j++;
	}
	$i++;
	}
	echo "</tr>";
	if($j==0)
		echo "<h2>No Movies Found</h2>";
}
else
	echo "<h2>No Result Found</h2>";
?>
</table>
<table style="width:1%;">
<tr><br/><br/>
<div style="font-size:30px;text-align:center;"><?php if($response["Response"]=="True") echo "Series";?></div>
<?php 
if(!empty($search) && $response["Response"]=="True")
{
	while($movie[$k])
	{
	ob_start();
	echo "Array(".$movie[$k].")";
	$info = ob_get_clean();
	$data=eval("return $info;");
	if($l%4==0)
		echo "</tr><tr>";
	if($data['Type']=="series")
	{
		echo "<td><a href='movie.php?id=".$data["imdbID"]."'><b>".$data['Title']." (".$data['Year'].")</a><br/><br/><a href='movie.php?id=".$data["imdbID"]."'><img  src=".$data['Poster']." align='center'><br/>"."</td>";
		$l++;
	}
	$k++;
	}
	echo "</tr>";
	if($l==0)
		echo "<h2>No Series Found</h2>";
}
?>
</table> 

<table style="width:1%;">
<tr><br/><br/>
<div style="font-size:30px;text-align:center;"><?php if($response["Response"]=="True") echo "Games";?></div>
<?php 
if(!empty($search) && $response["Response"]=="True")
{
	while($movie[$m])
	{
	ob_start();
	echo "Array(".$movie[$m].")";
	$info = ob_get_clean();
	$data=eval("return $info;");
	if($n%4==0)
		echo "</tr><tr>";
	if($data['Type']!="movie" && $data['Type']!="series")
	{
		if($data['Poster']=="N/A")
		{
			$data['Poster']='https://images-na.ssl-images-amazon.com/images/I/41iiLQdPFOL.jpg';
		}
		echo "<td><a href='movie.php?id=".$data["imdbID"]."'><b>".$data['Title']." (".$data['Year'].")</a><br/><br/><a href='movie.php?id=".$data["imdbID"]."'><img  src=".$data['Poster']." height='350px' align='center'><br/>"."</td>";
		$n++;
	}
	$m++;
	}
	echo "</tr>";
	if($n==0)
		echo "<h2>No Games Found</h2>";
}
?>
</table>
<form action="search.php" method="get">
<?php
if($response["Response"]=="True")
{
	if($_SESSION['page']>1)
	{
		echo "<button type='submit' class='waves-effect waves-light btn' name='prev_btn'><i class='material-icons left'>arrow_back</i>Prev</button> ";
		unset($_GET["next_btn"]);
		unset($_GET["jump_btn"]);
		unset($_GET["search_btn"]);
	}
	if($_SESSION['page']<1000)
	{
		echo "<button type='submit' class='waves-effect waves-light btn' name='next_btn'><i class='material-icons right'>arrow_forward</i>Next</button>";
		unset($_GET["prev_btn"]);
		unset($_GET["jump_btn"]);
		unset($_GET["search_btn"]);
	}
}
?>
</form>
<form action="search.php" method="get">
<?php
echo "<br><br><input type='text' data-length='3' style='width: 50px;' name='jump'><button type='submit' class='waves-effect waves-light btn' name='jump_btn'><i class='material-icons right'>arrow_forward</i>Jump to Page</button>";
?>
</form>
</body>
<script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="js/materialize.min.js"></script>
</html>