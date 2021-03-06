<?php

	require_once 'inc/connection.inc.php';
	require_once 'inc/function.inc.php';
?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
	<meta name="author" content="">
	<meta name="description" content="">
	<title>DuckDuckGo Word Meaning</title>

	<style>
		.github-corner:hover .octo-arm {
			animation: octocat-wave 560ms ease-in-out
		}
		@keyframes octocat-wave {
			0%, 100% {
				transform: rotate(0)
			}
			20%,
			60% {
				transform: rotate(-25deg)
			}
			40%,
			80% {
				transform: rotate(10deg)
			}
		}
		@media (max-width: 500px) {
			.github-corner:hover .octo-arm {
				animation: none
			}
			.github-corner .octo-arm {
				animation: octocat-wave 560ms ease-in-out
			}
		}
	</style>
</head>
<body bgcolor="#98AFC7">
	<a href="https://github.com/prachi1210/duckduckgo-word-meaning" class="github-corner" target="_blank">
		<svg width="100px" height="100px" viewBox="0 0 250 250" style="position: fixed; top: 0px; right: 0px; border: 0px;">
			<path d="M0,0 L115,115 L130,115 L142,142 L250,250 L250,0 Z" fill="#111"></path>
			<path class="octo-arm" d="M128.3,109.0 C113.8,99.7 119.0,89.6 119.0,89.6 C122.0,82.7 120.5,78.6 120.5,78.6 C119.2,72.0 123.4,76.3 123.4,76.3 C127.3,80.9 125.5,87.3 125.5,87.3 C122.9,97.6 130.6,101.9 134.4,103.2" fill="#ffffff" style="transform-origin: 130px 106px;"></path>
			<path class="octo-body" d="M115.0,115.0 C114.9,115.1 118.7,116.5 119.8,115.4 L133.7,101.6 C136.9,99.2 139.9,98.4 142.2,98.6 C133.8,88.0 127.5,74.4 143.8,58.0 C148.5,53.4 154.0,51.2 159.7,51.0 C160.3,49.4 163.2,43.6 171.4,40.1 C171.4,40.1 176.1,42.5 178.8,56.2 C183.1,58.6 187.2,61.8 190.9,65.4 C194.5,69.0 197.7,73.2 200.1,77.6 C213.8,80.2 216.3,84.9 216.3,84.9 C212.7,93.1 206.9,96.0 205.4,96.6 C205.1,102.4 203.0,107.8 198.3,112.5 C181.9,128.9 168.3,122.5 157.7,114.1 C157.9,116.9 156.7,120.9 152.7,124.9 L141.0,136.5 C139.8,137.7 141.6,141.9 141.8,141.8 Z" fill="#ffffff"></path>
		</svg>
	</a>
	<center>			
		<h1><font face ="Tw Cen MT" color="#151b54">Word Definition</h1>
		<h3><font face ="Tw Cen MT" color="#151b54">Powered By</h3>
		<img src="images/abc.png" alt="DuckDuckGo" >
	</center>
	<br>


	<table width="300" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#cccccc">
		<tr>
			<form method="POST">
				<td>
					<table width="100%" border="0"  cellspacing="1" bgcolor="#ffffff">
						<tr>
							<td colspan="3"><strong><center>Enter word definition to search</cemter></strong></td>
						</tr>
						<tr>
							<td width="78">Word</td>
							<td width="6">:</td>
							<td width="294"><input name="word" type="text" id="word"></td>
						</tr>
						<tr></tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td><input type="submit" name="Submit" value="GO"></td>
						</tr>
					</table>
				</td>
			</form>
		</tr>
	</table>
</body>
</html>
<?php
	if(!isset($_POST['word']))
		header("Location : index.php");
	else 
	{
		
		$word=$_POST['word'];
		$q1 = "SELECT `freq` FROM `words` WHERE `word`='$word'";
		
		if($query_run = mysqli_query($connection,$q1))
		{
			
			if(mysqli_num_rows($query_run) == 1 )
			{
					while($query_row = mysqli_fetch_assoc($query_run)){
					$freq = $query_row['freq'];
					$freq+=1;
					$q2="UPDATE `words` SET `freq`='$freq' WHERE `word`='$word'";
					(mysqli_query($connection,$q2));
						
				}
			}
			else 
			{
				
				$freq=1;
				$q2= "INSERT INTO `words` (`word`,`freq`) VALUES ('$word','$freq')";
				(mysqli_query($connection,$q2));
			}		
			
		} 
		

		$url='https://api.duckduckgo.com/?format=json&pretty=1&q='.$word;
		
		$json = file_get_contents($url);
		$obj = json_decode($json, true);
		
		if($obj["Heading"] == "")
			echo "No meaning found";
		else
			echo $obj["Heading"];
		
		foreach ($obj as $key => $value) 
		{
			if($key=="RelatedTopics")
			{

				foreach ($value as $k1 => $v1)
				{
					if($k1=="Text")
					{
						echo "<br>";
						print $v1["Result"];
					}
				}
			}
		}			
				
		echo "<br><br>"."<b>Frequency of search of </b><i>". $word. "</i><b> is </b>". $freq."</i>";
		$q3="SELECT * FROM `words` ORDER BY `freq` DESC";
		if($query_run = mysqli_query($connection,$q3))
		{
			$row = mysqli_fetch_assoc($query_run);	//select first row	
				echo "<br><br>"."<b>Word with max search frequency : </b><i>". $row['word']. "</i><b> with frequency </b>". $row['freq']."</i";				
		} 

		die;
	}

?>
