/**
* Some basic UI scripts
* "test your knowledge of some of the greatest games of all time"
*/


var gPlayerFBID;

FB.getLoginStatus(function(response) {
      if (response.status === 'connected') {
        gPlayerFBID = response.authResponse.userID;
        welcomePlayer(gPlayerFBID);
		processIncomingURL();
        //showScores();
      }
});

function welcomePlayer(uid) 
{
    var welcomeMsgContainer = document.createElement('div');
    welcomeMsgContainer.id = 'welcome_msg_container';
	welcomeMsgContainer.className = 'menu_item';
    topBar.appendChild(welcomeMsgContainer);

	//TODO: might want to store the response.first_name in a var to keep user name for later user 
	  FB.api('/me?fields=first_name', function(response) {
      var welcomeMsg = document.createElement('div');
      var welcomeMsgStr = 'Welcome, ' + response.first_name + '!';
      welcomeMsg.innerHTML = welcomeMsgStr;
      welcomeMsg.id = 'welcome_msg';
      welcomeMsgContainer.appendChild(welcomeMsg);

      var imageURL = 'https://graph.facebook.com/' + uid + '/picture?width=256&height=256';
      var profileImage = document.createElement('img');
      profileImage.setAttribute('src', imageURL);
      profileImage.id = 'welcome_img';
      profileImage.setAttribute('height', '148px');
      profileImage.setAttribute('width', '148px');
      welcomeMsgContainer.appendChild(profileImage);
  });

  /* Brag Button */
    var bragButton = document.createElement('div');
    bragButton.className = 'menu_item';
    bragButton.id = 'brag';
    bragButton.style.width = "459px";
    bragButton.style.height = "112px";
    bragButton.style.top = "308px";
    bragButton.style.left = "0px";
    bragButton.setAttribute('onclick', 'javascript:sendBrag()');
    bragButton.style.backgroundImage = "url('images/button_brag.png')";
    topBar.appendChild(bragButton);
  
  /* Challenge Button */
    var challengeButton = document.createElement('div');
    challengeButton.className = 'menu_item';
    challengeButton.id = 'challenge';
    challengeButton.style.width = "459px";
    challengeButton.style.height = "112px";
    challengeButton.style.top = "428px";
    challengeButton.style.left = "0px";
    challengeButton.setAttribute('onclick', 'javascript:sendChallenge()');
    challengeButton.style.backgroundImage = "url('images/button_challenge.png')";
	//challengeButton.style.cssFloat = "left";
    topBar.appendChild(challengeButton);
}

function sendChallenge() 
{
	//TODO: gScore should probably be loaded from database - it's the last score of the user ... maybe?
	//TODO: shouldn't be allowed to challenge when in the middle of gameplay ... should check the playing bool - EDIT: this should be fixed with "rightAnswers" var
	
	// if player has already completed a game, they will have gScore set - use this acheivement to challenge
	if (window.finalScore) {
		var challengeData = {"challenge_score" : window.finalScore}; // TODO: might want challenger name in this 
		FB.ui({method: 'apprequests',
		title: 'Gamer Game Challenge!',
		message: 'I scored ' + window.finalScore + ' correct answers on gaming knowledge! Can you beat it?',
		data: challengeData
		}, fbCallback);
		
	} else { // otherwise, if game hasn't been played and gScore hasn't been set
		FB.ui({method: 'apprequests',
		title: 'Play the Gamer Game!',
		message: 'Are you the smartest gamer?',
		}, fbCallback);
	}
	//console.log('send challenge');
}

function sendBrag() {
	if (window.finalScore) {
		FB.ui({ method: 'feed',
			caption: 'I scored ' + window.finalScore + ' correct answers on gaming knowledge! Can you beat it?',
			picture: 'http://localhost/gamer-game/images/logo_large.jpg',
			name: 'Checkout my Gamer Game greatness!',
			link: 'http://localhost/gamer-game?challenge_brag=' + gPlayerFBID
		}, fbCallback);
	}
	//console.log(window.finalScore);
}

function sendScore() {
/*
	if (gScore) {
		console.log("Posting score to Facebook");
		FB.api('/me/scores/', 'post', { score: gScore }, function(response) {
			console.log("Score posted to Facebook");
		});
}
*/
	console.log('send score');
}

/* TODO: needs to be tested */
function processIncomingURL() {
    var urlParams = {};
    (function () {
        var match,
        pl     = /\+/g,  // Regex for replacing addition symbol with a space
        search = /([^&=]+)=?([^&]*)/g,
        decode = function (s) { return decodeURIComponent(s.replace(pl, " ")); },
        query  = window.location.search.substring(1);

        while (match = search.exec(query))
            urlParams[decode(match[1])] = decode(match[2]);
    })();

    var requestType = urlParams["app_request_type"];

    if (requestType == "user_to_user") {
        var requestID = urlParams["request_ids"];  

        FB.api(requestID, function(response) {
            console.log(response);
            var gChallengerID = response.from.id;
            var gChallengerName = response.from.name.split(" ")[0];
			var gChallengerScore = response.from.data.challenger_score;
        });
    }

	// not sure if we really need this 
    var feedStorySender = urlParams["challenge_brag"];

    if (feedStorySender) {
        FB.api(feedStorySender, function(response) {
            console.log(response);
            var gChallengerName = response.first_name;
        });
    }
}

function fbCallback(response) {
console.log(response);
}