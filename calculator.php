<!-- Calculator Script -->
<?php
	//functions
	
	if (isset($_GET["endGame"]) && $_GET["endGame"] == 'yes') {
		session_destroy();
		//redirect to homepage and remove any get parameters
		$location="http://testing.pixel-fx.co.uk/index.php#game";
		echo '<META HTTP-EQUIV="refresh" CONTENT="0;URL='.$location.'">';
	}

	//Check if the number of players has been set
	
	if (isset($_POST['next'])) {
		//Determine thye number players
		foreach ($_POST['numberOfPlayers'] as $player) {
			$_SESSION['numberOfPlayers'] = $player;
			$location="http://testing.pixel-fx.co.uk/index.php#game";
			echo '<META HTTP-EQUIV="refresh" CONTENT="0;URL='.$location.'">';
		}

	}

	elseif (isset($_SESSION['numberOfPlayers'])) {
		$numberOfPlayers = $_SESSION['numberOfPlayers'];
		
		if (!isset($_POST['playerSubmit']) || (isset($_POST['scores']))) {
			?> 
                    <!-- Form to enter the playes names -->
                    <form method="post" action="">
                        <fieldset>
                            <?php 
			$x = 1;
			echo '<div class="custTitle"><h3>Enter player names below</h3></div>';
			while ($x <= $numberOfPlayers) {
				echo '<input class="form-control" type="text" name="players[]" placeholder="Player ' . $x . '">';
				$x++;
			}

			?>
                        </fieldset>
                        <input type="submit" name="playerSubmit" value="Start Game">
                        </form>
                    <?php
		}

		//end if
		
		if (isset($_POST['playerSubmit'])) {
			//$_SESSION['players'] = $players;
			//var_dump($_SESSION['players']);
			$playerstest = array();
			foreach ($_POST['players'] as $player) {
				$playerstest[] =  $player;
				//$_SESSION['players'][$key] = $player;
			}

			$_SESSION['players'] = $playerstest;
			//var_dump($_SESSION['players']);
		}

		//Check player name have been submitted
		
		if(isset($_POST['playerSubmit'])) {
			$players = $_SESSION['players'];
			?>
                    <!-- Form to enter players score-->
                    <form method="post" action="">
                    <?php
			$i = 0;
			while($i < $_SESSION['numberOfPlayers']){
				echo '<div class="styled-select">';
				echo '<fieldset>';
				echo '<div class="custTitle"><h3>' . $players[$i] . '</h3></div>';
				$x=1;
				while($x <= 21){
					?>
                            <label>Throw, <?php  echo $x; ?></label>
                            <select class="form-control" name="score[<?php  echo $i; ?>][<?php  echo $x; ?>]">
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">X</option>
                            </select>
                                    <?php
					$x++;
				}

				;
				?>
                                    <input type="hidden" name="player[<?php  echo $i; ?>]" value="<?php  echo $players[$i]  ?>">
                            </fieldset>
                            </div>
                                    <?php
 $i++;} ?>
                        <input type="submit" value="Finish Game" name="scores"/>
                </form>
                <?php 
		}

		
		if(isset($_POST['score'])) {
			$i = 0;
			$result = [];
			foreach($_SESSION['players'] as $name) {
				$result[$name] = $_POST['score'][$i];
				$pins = $_POST['score'][$i];
				$game = calculateScore($i, $pins);
				$i++;
			}

		}

	} else {
		?>
                    <div class="custTitle"
                        <h2>How many players will be bowling?</h2>
                    </div>
                    <!-- Form to select the number players -->
                    <form method="post" action="">
                            <select class="form-control" name="numberOfPlayers[]">
                                <option name="numberOfPlayers[]" value="1">1</option>
                                <option name="numberOfPlayers[]" value="2">2</option>
                                <option name="numberOfPlayers[]" value="3">3</option>
                                <option name="numberOfPlayers[]" value="4">4</option>
                                <option name="numberOfPlayers[]" value="5">5</option>
                                <option name="numberOfPlayers[]" value="6">6</option>
                            </select>
                        <input type="submit" name="next" value="Next">
                    </form>
                <?php
	}

	
	if (isset($_SESSION['numberOfPlayers'])) {
		echo '<button class="btn_lrg"><span><a href="http://testing.pixel-fx.co.uk/index.php?endGame=yes">New Game</a></span></button>';
	}

	
	function calculateScore($player, $pins) {
		//$players = $_SESSION['players'];
		global $players;
		$frame = 0;
		$frameScore = array(0);
		//loop for 10 frames of a game
		while ($frame <=9) {
			$frameScore[$frame] = array_shift($pins);
			//Check for a strike
			
			if($frameScore[$frame] == 10) {
				$frameScore[$frame] = (10 + $pins[0] + $pins[1]);
				// No strike, so take in two throws    
			} else {
				$frameScore[$frame] = $frameScore[$frame] + array_shift($pins);
				//Check for a spare
				
				if($frameScore[$frame] == 10) {
					$frameScore[$frame] = (10 + $pins[0]);
				}

			}

			// End if/else
			//Move to the next frame    
			$frame++;
		}

		//end while
		//echo out player scoreborad
		echo '<div class="col-xs-6 col-md-4 scores">' . '<h3>' .$_SESSION["players"][$player]. '</h3>' . '<h4>' . array_sum($frameScore) . '</h4></div>';
	}

	//end function
	?>
                <!-- End Calculator Script -->