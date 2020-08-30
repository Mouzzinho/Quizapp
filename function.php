<?php

function conn() {
		$result = @new mysqli('localhost','userplayer','Myszan123','sz_napierajczyk');
		if(mysqli_connect_errno())
		{
			echo'<p>Podłączenie nie powiodło się:'.mysqli_connect_error().'</p>';
			exit();
		} else {
			$result -> query("Set names 'utf8'");						
			return $result;
		}
    }
    ?>