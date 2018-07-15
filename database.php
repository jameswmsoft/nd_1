<?php
							$hostname = "localhost";
							$username = "ab4127_sb";
							$password = "sxT=x[a_rKXC";
							$database = "ab4127_sb";
							$link = mysqli_connect($hostname, $username, $password, $database);
							if(!$link){
								echo "Error: Unable to connect to MySQL." . PHP_EOL;
								echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
								echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
								exit;
							}
						?>