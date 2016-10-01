<?php
  include("C:\Users\Neelu\Desktop\soft\part2_php\public_html\session.php");
  
ini_set("display_errors", 0);
  

?>
<html>
   
   <script>
   
	
	
	function Inc(id,fi,mi)    {
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
			
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					var htmldata = xmlhttp.responseText;
					
					htmldata = htmldata.replace(/\\/gi , "");
					var stringIndex = htmldata.substring(htmldata.lastIndexOf("start") +5 ,htmldata.lastIndexOf("end"));
					
					var divtagNumbers = stringIndex.split("Begin");
					for (i = 1; i < divtagNumbers.length; i++) { 
							document.getElementById(divtagNumbers[i].split(" === ")[0]).innerHTML = divtagNumbers[i].split(" === ")[1];
					}
					
				}	
			};
			xmlhttp.open("GET","welcome.php?q="+fi+"&h="+id+"&f="+mi,true);
			xmlhttp.send();
			
        }
</script>
   
   <head>
      <title>Welcome <?php echo $_SESSION['login_user']; ?></title>
   </head>
   
   <body>
      <h3>Welcome <?php echo $_SESSION['login_user']; ?></h3> 
	  <div align = "center">
         <div style = "width:1000px; border: solid 1px #333333; " align = "left">
            <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>All files and There respective Url's with date</b></div>
				
            <div style = "margin:30px">
               
               <form action = "" method = "post">
                  	  
				  <?php
								$q = $_REQUEST["q"];
								$h = $_REQUEST["h"];
								$p = $_REQUEST["f"];
								
								
								
								if($q == null) {
									$sql = "SELECT distinct filename,fm.file_id as file_id,date FROM FILE_METADATA as fm join URL as u on fm.file_id = u.file_id where u.status = true  order by fm.date desc";
									$result = mysqli_query($mysqli,$sql);
									
									
											while ($row = $result->fetch_assoc()) {
												
												$sql7 = "update url set resolved = true";
												mysqli_query($mysqli ,$sql7);
												
												
												echo "<div id='". $row['file_id'] ."'>";
												$sql = "SELECT URL_NAME,URL_ID FROM URL WHERE file_id = '". $row['file_id'] ."' and status = true";
												$result1 = mysqli_query($mysqli,$sql);
												
												echo "<table><th align=\"left\">File: \"". $row['filename'] ."\" ran on \"". $row['date'] ."\"</th><br />";
												while ($row1 = $result1->fetch_assoc()) {
													echo "<tr><td width=\"700\"><a href=\"" . $row1['URL_NAME'] . "\">". $row1['URL_NAME']  ."</a></td><td><input type=\"button\" value=\"Ignore\" onclick=\"Inc( ". $row1['URL_ID'] . " , ". $row['file_id'] . ",'Ignore' )\"/> </td><td><input type=\"button\" value=\"Resolved\" onclick=\"Inc( ". $row1['URL_ID'] . " , ". $row['file_id'] . ", 'Resolve' )\"/> </td></tr>";
												}
												echo "</table>";
												echo "</div><br /><br />";
											}
								} else {
									if($p=="Ignore"){
										echo add($q,$h);
										
									} else {
										echo add3($q,$h);
									}
									echo add2($q,$h);
									
								}
								
							function add2($b,$a){
								global $mysqli;
									$b=intval($b);
									$a=intval($a);
								$sql3 = "select file_id, filename, date from FILE_METADATA as fm where filename = (select filename from FILE_METADATA where file_id = '$b') and fm.date <= (select date from FILE_METADATA where file_id = '$b')";
									$result3 = mysqli_query($mysqli ,$sql3);
									
									$data="start";
									while ($row3 = $result3->fetch_assoc()) {
										$data = $data . "Begin";
										$data = $data  . $row3['file_id'] . " === ";
										$sql = "SELECT URL_NAME,URL_ID FROM URL WHERE file_id = '". $row3['file_id'] ."' and status = true and resolved = true";
										$result1 = mysqli_query($mysqli,$sql);
										$rowcount=mysqli_num_rows($result1);
										if($rowcount != 0) {
											$data = $data  . "<table><th align=\"left\">File: \"". $row3['filename'] ."\" ran on \"". $row3['date'] ."\"</th><br />";
											while ($row1 = $result1->fetch_assoc()) {
												$data = $data  . "<tr><td width=\"700\"><a href=\"" . $row1['URL_NAME'] . "\">". $row1['URL_NAME']  ."</a></td><td><input type=\"button\" value=\"Ignore\" onclick=\"Inc( ". $row1['URL_ID'] . " , ". $row3['file_id'] . ",'Ignore' )\"/> </td><td><input type=\"button\" value=\"Resolved\" onclick=\"Inc( ". $row1['URL_ID'] . " , ". $row3['file_id'] . ", 'Resolve' )\"/> </td></tr>";
											}
											$data = $data  . "</table>";
										}
									}
									
									return json_encode($data."end");
							}	
  
							  function add($b,$a) {
									global $mysqli;
									$b=intval($b);
									$a=intval($a);
									
									$dt="";
									$sql5 = "select url_name from url where  url_id ='$a'";
									$result5 = mysqli_query($mysqli,$sql5);
									while ($row5 = $result5->fetch_assoc()) {
										$dt = $row5['url_name'];
									}
									
									$sql4 = "update url set status = false where  url_name ='$dt' and file_id in (select file_id from FILE_METADATA as fm where filename = (select filename from FILE_METADATA where file_id = '$b') and fm.date <= (select date from FILE_METADATA where file_id = '$b'))";
									if(mysqli_query($mysqli ,$sql4)){
										return json_encode($sql4);
									}
									return json_encode($sql4);
								
								}
								
								function add3($b,$a) {
									global $mysqli;
									$b=intval($b);
									$a=intval($a);
									
									$dt="";
									$sql5 = "select url_name from url where  url_id ='$a'";
									$result5 = mysqli_query($mysqli,$sql5);
									while ($row5 = $result5->fetch_assoc()) {
										$dt = $row5['url_name'];
									}
									
									$sql4 = "update url set resolved = false where  url_name ='$dt' and file_id in (select file_id from FILE_METADATA as fm where filename = (select filename from FILE_METADATA where file_id = '$b') and fm.date <= (select date from FILE_METADATA where file_id = '$b'))";
									if(mysqli_query($mysqli ,$sql4)){
										return json_encode($sql4);
									}
									return json_encode($sql4);
								
								}
									
					?>
					
					
					
					
				  
               </form>
               
					
            </div>
				
         </div>
			
      </div>
   </body>
   
</html>