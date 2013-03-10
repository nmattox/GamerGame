<!DOCTYPE HTML>

<html>
    <head>

        <style>
            body {
            	background:url('bg.jpg') repeat-x #0c0d10;
		  }

		  #ccontainer{
		  width:550px;

		  margin: 0 auto;
		  margin-top:100px;
		  }

		  #myCanvas {

		  }

        </style>





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
				var QuizFinished = false;
				var lock = false;
				var textpos1=45;
				var textpos2=145;
				var textpos3=230;
				var textpos4=325;
				var Questions = new Array;
				var Options = new Array;
				


        <?php


					 

					$datastr = "data".strval($_GET["q"]).".xml";
					$xml = simplexml_load_file($datastr);
					
					$counter= count($xml);
					 

					for($i=0;$i<$counter;$i++){
					echo "Questions[".$i."]='".$xml-> task[$i]->question ."';";
					echo "\n";
					echo "Options[".$i."]=['".$xml-> task[$i]->option[0] ."','";
					echo $xml-> task[$i]->option[1] ."','";
					echo $xml-> task[$i]->option[2]."'];";
					echo "\n";
					}

				
?>




				quizbg.onload = function(){
			      context.drawImage(quizbg, 0, 0);
				  SetQuestions();
				}//quizbg
				quizbg.src = "quizbg.png";



				SetQuestions = function(){

					Question=Questions[qnumber];
					CorrectAnswer=1+Math.floor(Math.random()*3);

					if(CorrectAnswer==1){Option1=Options[qnumber][0];Option2=Options[qnumber][1];Option3=Options[qnumber][2];}
					if(CorrectAnswer==2){Option1=Options[qnumber][2];Option2=Options[qnumber][0];Option3=Options[qnumber][1];}
					if(CorrectAnswer==3){Option1=Options[qnumber][1];Option2=Options[qnumber][2];Option3=Options[qnumber][0];}

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

			if(lock){
				ResetQ();
			}//if lock

			else{

			if(my>110 && my<180){GetFeedback(1);}
			if(my>200 && my<270){GetFeedback(2);}
			if(my>290 && my<360){GetFeedback(3);}

			}//!lock

				}//ProcessClick



		GetFeedback = function(a){

		  if(a==CorrectAnswer){
		  	context.drawImage(quizbg, 0,400,75,70,480,110+(90*(a-1)),75,70);
			rightanswers++;
			//drawImage(image, sx, sy, sWidth, sHeight, dx, dy, dWidth, dHeight)
		  }
		  else{
		    context.drawImage(quizbg, 75,400,75,70,480,110+(90*(a-1)),75,70);
			wronganswers++;
		  }
		  lock=true;
		  context.font = "14pt Calibri,Arial";
		  context.fillText("Click again to continue",20,380);
		}//get feedback


		ResetQ= function(){
		lock=false;
		context.clearRect(0,0,550,400);
		qnumber++;
		if(qnumber==Questions.length){EndQuiz();}
		else{
		context.drawImage(quizbg, 0, 0);
		SetQuestions();}
		}


		EndQuiz=function(){
		canvas.removeEventListener('click',ProcessClick,false);
		context.drawImage(quizbg, 0,0,550,90,0,0,550,400);
		context.font = "20pt Calibri,Arial";
		context.fillText("You have finished the quiz!",20,100);
		context.font = "16pt Calibri,Arial";
		context.fillText("Correct answers: "+String(rightanswers),20,200);
		context.fillText("Wrong answers: "+String(wronganswers),20,240);
		}
			};//windowonload

        </script>


    </head>
    <body>

    <div id="ccontainer">
<canvas id="myCanvas" width="550" height="400"></canvas>
        </div>


</body>
</html>