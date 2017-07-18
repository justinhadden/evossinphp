<!doctype html>
<html lang"en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EVOSS results</title>
	<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="./css/theStyles.css">
	<script src="./js/jquery-3.1.1.min.js"></script>
	<script src="./js/bootstrap.min.js"></script>
	<script src="./js/myjs.js"></script>
</head>
<body>
	<h2><?php echo $_POST['ssdate']." | ".$_POST['ssshiftnum']." | ".$_POST['ssjobcode'] ?></h2>
	<table>
		<tr>
			<th>First 4 <?php echo $f4; ?></th>
			<th>Full 8 <?php echo $f8; ?></th>
			<th>Last 4 <?php echo $l4; ?></th>
		</tr>
		
		<?php
			foreach($results as $row)
			{
				if($row['OTBlock'] == '1')
				{
					?>
					<tr>
						<td><?php echo $row['Name']; ?></td>
						<td <?php ?>></td>
						<td></td>
					</tr>
					<?php
				}
				elseif ($row['OTBlock'] == '2') {
					?>
					<tr>
						<td></td>
						<td><?php echo $row['Name']; ?></td>
						<td></td>
					</tr>
					<?php
				}
				else {
					?>
					<tr>
						<td></td>
						<td></td>
						<td><?php echo $row['Name']; ?></td>
					</tr>
					<?php
				}
			}
		?>
		
	</table>

<form <?php echo "action='?date=".$_POST['ssdate']."&shift=".$_POST['ssshiftnum']."&jobcode=".$_POST['ssjobcode']."'"; ?> method="post">
		<input type="text" name="supid">
		<input type="text" name="otblock">
		<input class="btn btn-primary" type="submit" value="Generated Need">
		<a href="?home" class="btn btn-primary">Home</a>
	</form>	

</body>
</html>