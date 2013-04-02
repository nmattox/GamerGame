<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>The Gamer Game</title>

<?php
    require 'server/fb-php-sdk/facebook.php';

    $app_id = '493343430730104';
    $app_secret = '32087faf725e5487afd4a733df0dfbfa';
    $app_namespace = 'thegamergame';
    $app_url = 'https://apps.facebook.com/' . $app_namespace . '/';
    $scope = 'email,publish_actions';

    // Init the Facebook SDK
    $facebook = new Facebook(array(
         'appId'  => $app_id,
         'secret' => $app_secret,
));

// Get the current user
$user = $facebook->getUser();

// If the user has not installed the app, 	 them to the Login Dialog
if (!$user) {
        $loginUrl = $facebook->getLoginUrl(array(
        'scope' => $scope,
        'redirect_uri' => $app_url,
        ));

        print('<script> top.location.href=\'' . $loginUrl . '\'</script>');
}
?>

<link rel="stylesheet" type="text/css" href="mainStyles.css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script src="script.js"></script>

        <script>
            window.onload = function(){
                var canvas = document.getElementById("myCanvas");
                var context = canvas.getContext("2d");
				var quizbg = new Image();
				var Question = new String;
				var Option1 = new String;
				var Option2 = new String;
				var Option3 = new String;
				var mx=0;
				var my=0;
				var CorrectAnswer = 0;
				var qnumber = 0;
				var rightanswers=0;
				var wronganswers=0;
				// global quiz finished variable 
				// TODO: See if there are any other variables that should be global
				window.QuizFinished = false;
				var lock = false;
				var textpos1=45;
				var textpos2=145;
				var textpos3=230;
				var textpos4=325;
				// each array is indexed at the Q number 
				var Questions = new Array;
				var Options = new Array;
				var Images = new Array;
				var CorrectAnswers = new Array;
				window.finalScore = 0;
				window.inGame = true;
				var questionAnswered = false;

        <?php


					 

					//$datastr = "data".strval($_GET["q"]).".xml";
					$datastr = "data2.xml";
					$xml = simplexml_load_file($datastr);
					
					$counter= count($xml);
					 
					// TODO: what do the \n s do?
					for($i=0;$i<$counter;$i++){
					echo "Questions[".$i."]='".$xml-> task[$i]->question ."';";
					echo "\n";
					echo "Options[".$i."]=['".$xml-> task[$i]->option[0] ."','";
					echo $xml-> task[$i]->option[1] ."','";
					echo $xml-> task[$i]->option[2]."'];";
					$attr = 'correct';
					if($xml->task[$i]->option[0]->attributes()->$attr == 'true')
					{
						echo "CorrectAnswers[".$i."]=1";
					}
					if($xml->task[$i]->option[1]->attributes()->$attr == 'true')
					{
						echo "CorrectAnswers[".$i."]=2";
					}
					if($xml->task[$i]->option[2]->attributes()->$attr == 'true')
					{
						echo "CorrectAnswers[".$i."]=3";
					}
					echo "\n";
					echo "Images[".$i."]='".$xml->task[$i]->img."';";
					}

				
?>
	

				quizbg.onload = function(){
			      context.drawImage(quizbg, 0, 0);
				  SetQuestions();
				}//quizbg
				quizbg.src = "img/quizbg.png";


				SetQuestions = function(){

					Question=Questions[qnumber];
					CorrectAnswer=1+Math.floor(Math.random()*3);

					if(CorrectAnswer==1)
					{
						Option1=Options[qnumber][0];
						Option2=Options[qnumber][1];
						Option3=Options[qnumber][2];
					}
					if(CorrectAnswer==2)
					{
						if(CorrectAnswers[qnumber] == 1)
						{
							CorrectAnswers[qnumber] = 2;
						}
						else if(CorrectAnswers[qnumber] == 2)
						{
							CorrectAnswers[qnumber] = 3;
						}
						else if(CorrectAnswers[qnumber] == 3)
						{
							CorrectAnswers[qnumber] = 1;
						}
						Option1=Options[qnumber][2];
						Option2=Options[qnumber][0];
						Option3=Options[qnumber][1];
					}
					if(CorrectAnswer==3)
					{
						if(CorrectAnswers[qnumber] == 1)
						{
							CorrectAnswers[qnumber] = 3;
						}
						else if(CorrectAnswers[qnumber] == 2)
						{
							CorrectAnswers[qnumber] = 1;
						}
						else if(CorrectAnswers[qnumber] == 3)
						{
							CorrectAnswers[qnumber] = 2;
						}
						Option1=Options[qnumber][1];
						Option2=Options[qnumber][2];
						Option3=Options[qnumber][0];
					}

					context.textBaseline = "middle";
					context.font = "24pt Calibri,Arial";
					context.fillText(Question,20,textpos1);
					context.font = "18pt Calibri,Arial";
					context.fillText(Option1,20,textpos2);
					context.fillText(Option2,20,textpos3);
					context.fillText(Option3,20,textpos4);


				}//SetQuestions

				canvas.addEventListener('click',ProcessClick,false);

				function ProcessClick(ev) {

				mx=ev.x-canvas.offsetLeft;
				my=ev.y-canvas.offsetTop;
				
				if(ev.x == undefined){
					mx = ev.pageX - canvas.offsetLeft;
					my = ev.pageY - canvas.offsetTop;
				}

			// lock is set when question has been anaswered. 
			// resetQ() goes to next Q
			/*
			if(lock){
				ResetQ();
			}//if lock
			
			else{*/

			if(my>110 && my<180){GetFeedback(1);}
			if(my>200 && my<270){GetFeedback(2);}
			if(my>290 && my<360){GetFeedback(3);}

			//}//!lock

				}//ProcessClick



		GetFeedback = function(a){
		console.log(a);
		console.log(CorrectAnswers[qnumber]);
		if(!questionAnswered)
		{
		// determines if answer is correct or not 
		  if(a==CorrectAnswers[qnumber]){
		  	context.drawImage(quizbg, 0,400,75,70,480,110+(90*(a-1)),75,70);
			rightanswers++;
			//drawImage(image, sx, sy, sWidth, sHeight, dx, dy, dWidth, dHeight)
		  }
		  else{
		    context.drawImage(quizbg, 75,400,75,70,480,110+(90*(a-1)),75,70);
			wronganswers++;
		  }
		  questionAnswered = true;
		 }
		  // click next to continue 
		  //lock=true;
		  //context.font = "14pt Calibri,Arial";
		  //context.fillText("Click again to continue",20,380);
		}//get feedback


		ResetQ= function(){
			if(!questionAnswered)
			{
				wronganswers++;
			}
			questionAnswered = false;
			lock=false;
			context.clearRect(0,0,550,400);
			qnumber++;
			if(qnumber==Questions.length){EndQuiz();}
			else{
				context.drawImage(quizbg, 0, 0);
				SetQuestions();
			}
		}

		ResetQBack = function()
		{
			/*
			if(qnumber != 0)
			{
				lock=false;
				context.clearRect(0,0,550,400);
				qnumber--;
				if(qnumber==Questions.length){EndQuiz();}
				else{
					context.drawImage(quizbg, 0, 0);
					SetQuestions();
				}
			}
			*/
		}

		EndQuiz=function(){
		window.QuizFinished = true;
		canvas.removeEventListener('click',ProcessClick,false);
		context.drawImage(quizbg, 0,0,550,90,0,0,550,400);
		context.font = "20pt Calibri,Arial";
		context.fillText("You have finished the quiz!",20,100);
		context.font = "16pt Calibri,Arial";
		context.fillText("Correct answers: "+String(rightanswers),20,200);
		context.fillText("Wrong answers: "+String(wronganswers),20,240);
		window.finalScore = rightanswers;
		window.inGame = false;
		console.log('end game, inGame boolean = '+window.inGame);
		}
		
		
			};//windowonload

        </script>

</head>

<body>
      <div id="fb-root"></div>
      <script src="//connect.facebook.net/en_US/all.js"></script>
	  
<div id="topBar"></div>
<br clear="all" />	  
<div id="slideshow">

	<ul class="slides">
    	<li><img src="img/photos/1.jpg" width="620" height="320" alt="1" /></li>
        <li><img src="img/photos/2.jpg" width="620" height="320" alt="2" /></li>
        <li><img src="img/photos/3.jpg" width="620" height="320" alt="3" /></li>
		<li><img src="img/photos/4.jpg" width="620" height="320" alt="3" /></li>
		<li><img src="img/photos/5.jpg" width="620" height="320" alt="3" /></li>
		<li><img src="img/photos/6.jpg" width="620" height="320" alt="3" /></li>
		<li><img src="img/photos/7.jpg" width="620" height="320" alt="3" /></li>
		<li><img src="img/photos/8.jpg" width="620" height="320" alt="3" /></li>
		<li><img src="img/photos/9.jpg" width="620" height="320" alt="3" /></li>
		<li><img src="img/photos/10.jpg" width="620" height="320" alt="3" /></li>
		<li><img src="img/photos/11.jpg" width="620" height="320" alt="3" /></li>
		<li><img src="img/photos/12.jpg" width="620" height="320" alt="3" /></li>
		<li><img src="img/photos/13.jpg" width="620" height="320" alt="3" /></li>
		<li><img src="img/photos/14.jpg" width="620" height="320" alt="3" /></li>
		<li><img src="img/photos/15.jpg" width="620" height="320" alt="3" /></li>
		<li><img src="img/photos/16.png" width="620" height="320" alt="3" /></li>
		<li><img src="img/photos/17.jpg" width="620" height="320" alt="3" /></li>
		<li><img src="img/photos/18.jpg" width="620" height="320" alt="3" /></li>
		<li><img src="img/photos/19.jpg" width="620" height="320" alt="3" /></li>
		<li><img src="img/photos/20.jpg" width="620" height="320" alt="3" /></li>
		<li><img src="img/photos/game_over.PNG" width="620" height="320" alt="3" /></li>
    </ul>
    <span class="arrow next"></span>
</div>

<div id="ccontainer">
	<canvas id="myCanvas" width="550" height="400"></canvas>
</div>
	  
		<script>
    var appId = '<?php echo $facebook->getAppID() ?>';
    // Initialize the JS SDK
    FB.init({
        appId: appId,
        cookie: true,
		frictionlessRequests: true,
    });

    FB.getLoginStatus(function(response) {
        uid = response.authResponse.userID ? response.authResponse.userID : null;
    });
</script>

<script src="scripts/ggui.js"></script>
	
</body>
</html>