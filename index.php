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
</style>
<body>
<div class="bgimg w3-display-container w3-animate-opacity w3-text-white">
<div class="fixed-action-btn horizontal w3-display-topright">
	<form method="get" action="search.php">
		<input id="main_search_input" type="text" name="search" placeholder="Search" />
		<button id="search_button" type="submit" class="btn btn-small blue darken" name="search_btn"><i class="material-icons">search</i></button>
	</form>
</div>
</div>
  <div class="w3-display-middle">
    <h1 class="w3-jumbo w3-animate-top blue-grey-text text-lighten-4">OMDb Database</h1>
    <hr class="w3-border-grey" style="margin:auto;width:100%">
    <p class="w3-large w3-center blue-grey-text text-lighten-5">Database of thousands of movies</p>
  </div>
</div>
</body>
<script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="js/materialize.min.js"></script>
</html>