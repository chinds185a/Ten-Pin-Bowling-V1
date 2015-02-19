<!-- Bowling Calculator Script -->
<?php
session_start();	
    //start a new game
	if (isset($_GET["endGame"]) && $_GET["endGame"] == 'yes') {
		session_destroy();
		//redirect to homepage and remove any get parameters
		$location="http://localhost/test/calculator.php";
		echo '<META HTTP-EQUIV="refresh" CONTENT="0;URL='.$location.'">';
	}

	//Check if the number of players has been set
    if (isset($_POST['next'])) {
        //Determine thye number players
		foreach ($_POST['numberOfPlayers'] as $player) {
			$_SESSION['numberOfPlayers'] = $player;
			$location="http://localhost/test/calculator.php";
			echo '<META HTTP-EQUIV="refresh" CONTENT="0;URL='.$location.'">';
		}
    //check number of players has been set before enter player names
	} elseif (isset($_SESSION['numberOfPlayers'])) {
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
		}// close if
		
		if (isset($_POST['playerSubmit'])) {
			
			$players = array();
			foreach ($_POST['players'] as $player) {
				$players[] =  $player;
			}
            
            // save player names array to session for reuse
			$_SESSION['players'] = $players;
		}

		//Check player name have been submitted
		if(isset($_POST['playerSubmit'])) {
            
            //create global variable of players array
			$players = $_SESSION['players'];
?>
            
            <!-- Form to enter players score-->
            <form method="post" action="">
<?php
			$i = 0;
			while($i < $_SESSION['numberOfPlayers']){

				echo '<fieldset>';
				echo '<h3>' . $players[$i] . '</h3>';
                
				$x=1;
                // loop the form 21 times for  maximum of 21 throws
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
				}// close 21 throws while
?>
                        <input type="hidden" name="player[<?php  echo $i; ?>]" value="<?php  echo $players[$i]  ?>">
                    </fieldset>

<?php
                $i++;
            }// close while for looping throuhg players
?>
                    <input type="submit" value="Finish Game" name="scores"/>
                </form>
<?php 
		}// close player submit if

		// check if any scores have been submitted
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

    // if number of players is not yet set the show the form to select number of players
	} else {

?>
        <h2>How many players will be bowling?</h2>
                    
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
        
	}// close else
    
    // if the number of players has been set show a 'New Game' button
	if (isset($_SESSION['numberOfPlayers'])) {
		echo '<button class="btn_lrg"><span><a href="http://localhost/test/calculator.php?endGame=yes">New Game</a></span></button>';
	}

	// function to calculate the players scores
	function calculateScore($player, $pins) {
		global $players;
		$frame = 0;
        // create an array for the frame score
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

			}// close if

			//Move to the next frame and loop again  
			$frame++;
		}// close while
        
		//echo out player scoreborad
		echo '<h3>' .$_SESSION["players"][$player]. '</h3>' . '<h4>' . array_sum($frameScore) . '</h4>';
	}// end calculateScore function
?>
<!-- End Bowling Calculator Script -->