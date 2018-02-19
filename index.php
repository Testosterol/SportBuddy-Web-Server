<?php
error_reporting(E_ALL ^ E_DEPRECATED);
/**
 * File to handle all API requests
 * Accepts GET and POST
 *
 * Each request will be identified by TAG
 * Response will be JSON data

  /**
 * check for POST request
 */
if (isset($_POST['tag']) && $_POST['tag'] != '') {
    // get tag
    $tag = $_POST['tag'];

    // include DB_function
    require_once 'DB_Functions.php';
    $db = new DB_Functions();

    // response Array
    $response = array("tag" => $tag, "error" => FALSE);

    // checking tag
    if ($tag == 'login') {
        // Request type is check Login
        $email = $_POST['email'];
        $password = $_POST['password'];

        // check for user
        $user = $db->getUserByEmailAndPassword($email, $password);
        if ($user != false) {
            // user found
            $response["error"] = FALSE;
            $response["user_id"] = $user["user_id"];
            $response["is_active"] = $user["is_active"];
            $response["user"]["name"] = $user["user_name"];
            $response["userEmail"] = $user["user_email"];
            echo json_encode($response);
        } else {
            // user not found
            // echo json with error = 1
            $response["error"] = TRUE;
            $response["error_msg"] = "Incorrect email or password!";
            echo json_encode($response);
        }

    } else if ($tag == 'getUserInformation'){
      // Request type is Register new user
      $email = $_POST['email'];
      $user = $db->getUserProfileInformation($email);
      if ($user != false) {
          // user found
          $response["error"] = FALSE;
          $response["user_id"] = $user["user_id"];
          $response["user_name"] = $user["user_name"];
          $response["user_age"]= $user["user_age"];
          $response["user_weight"] = $user["user_weight"];
          $response["user_height"] = $user["user_height"];
          $response["user_sports"]= $user["user_sports"];
          $response["user_email"] = $user["user_email"];
          $response["user_profilePicture"] = $user["user_profilePicture"];
          echo json_encode($response);
      }else {
          // user not found
          // echo json with error = 1
          $response["error"] = TRUE;
          $response["error_msg"] = "Error occured in getting user information!";
          echo json_encode($response);
      }
}else if ($tag == 'retreiveEventNameAndPicture'){
  // Request type is Register new user
  $eventId = $_POST['eventId'];
  $user = $db->retreiveEventNameAndPicture($eventId);
  if ($user != false) {
      // user found
      $response["error"] = FALSE;
      $response["event_id"] = $user["event_id"];
      $response["event_name"] = $user["event_name"];
      $response["event_picture"]= $user["event_picture"];
      echo json_encode($response);
  }else {
      // user not found
      // echo json with error = 1
      $response["error"] = TRUE;
      $response["error_msg"] = "Error occured in getting user information!";
      echo json_encode($response);
  }
}else if ($tag == 'retreiveEventsForCurrentUser'){
  // Request type is Register new user
  $eventId = $_POST['idOfUser'];
  $eventNum = $_POST['eventNum'];
  $user = $db->retreiveEventsIdForCurrentUser($eventId,$eventNum);
  if ($user != false) {
      // user found
      $response["error"] = FALSE;
      $response["event_id"] = $user["event_id"];
      echo json_encode($response);
  }else {
      // user not found
      // echo json with error = 1
      $response["error"] = TRUE;
      $response["error_msg"] = "Error occured in getting user information!";
      echo json_encode($response);
  }
}else if ($tag == 'getTotalId'){
  // Request type is Register new user
  $email = $_POST['email'];
  $user = $db->getTotalId($email);
  if ($user != false) {
      // user found
      $response["error"] = FALSE;
      $response["event_id"] = $user["event_id"];
      echo json_encode($response);
  }else {
      // user not found
      // echo json with error = 1
      $response["error"] = TRUE;
      $response["error_msg"] = "Error occured in getting user information!";
      echo json_encode($response);
  }
}else if ($tag == 'sendEmailToServer'){
        // Request type is Register new user
        $helpText = $_POST['helpText'];
        $userID = $_POST['userID'];
        $userEmail = $_POST['userEmail'];
        $user = $db->storeEmailIntoDb($helpText, $userID, $userEmail);
        if ($user != false) {
            $response["error"] = FALSE;
            echo json_encode($response);
        }else {
            $response["error"] = TRUE;
            $response["error_msg"] = "Error occured in getting user information!";
            echo json_encode($response);
        }
    }else if ($tag == 'getTotalIdOfUser'){
  // Request type is Register new user
  $idOfUser = $_POST['idOfUser'];
  $user = $db->getTotalIdOfUser($idOfUser);
  if ($user != false) {
      // user found
      $response["error"] = FALSE;
      $response["event_id"] = $user["event_id"];
      echo json_encode($response);
  }else {
      // user not found
      // echo json with error = 1
      $response["error"] = TRUE;
      $response["error_msg"] = "Error occured in getting user information!";
      echo json_encode($response);
  }
}else if ($tag == 'save_Profile'){
  // Request type is Register new user
  $email = $_POST['email'];
  $name = $_POST['name'];
  $age = $_POST['age'];
  $weight = $_POST['weight'];
  $height = $_POST['height'];
  $sports = $_POST['sports'];
  $uzivatel = $db->storeUserProfileData($email,$name,$age,$weight,$height,$sports);
  if ($uzivatel != false) {
    $response["error"] = FALSE;
    $response["user_id"] = $uzivatel["user_id"];
    $response["user_name"] = $uzivatel["user_name"];
    $response["user_age"]= $uzivatel["user_age"];
    $response["user_weight"] = $uzivatel["user_weight"];
    $response["user_height"] = $uzivatel["user_height"];
    $response["user_sports"]= $uzivatel["user_sports"];
    $response["user_email"] = $uzivatel["user_email"];
    echo json_encode($response);
  }else {
      // user not found
      // echo json with error = 1
      $response["error"] = TRUE;
      $response["error_msg"] = "Error occured in Saving data";
      echo json_encode($response);
  }
}else if ($tag == 'removeUserFromEvent'){
  // Request type is Register new user
  $eventId = $_POST['eventId'];
  $userId = $_POST['userId'];
  $uzivatel = $db->removeUserFromCurrentEvent($eventId,$userId);
  if ($uzivatel != false) {
    $response["error"] = FALSE;
    $response["user_id"] = $uzivatel["user_id"];
    $response["countPeopleJoined"] = $uzivatel["countPeopleJoined"];
    echo json_encode($response);
  }else {
      // user not found
      // echo json with error = 1
      $response["error"] = TRUE;
      $response["error_msg"] = "Error occured in Saving data";
      echo json_encode($response);
  }
}else if ($tag == 'CheckNumberOfEventsForUser'){
  // Request type is Register new user
  $userId = $_POST['userId'];
  $uzivatel = $db->checkNumberOfEventsForUser($userId);
  if ($uzivatel != false) {
    $response["error"] = FALSE;
    $response["event_id"] = $uzivatel["event_id"];
    echo json_encode($response);
  }else {
      // user not found
      // echo json with error = 1
      $response["error"] = TRUE;
      $response["error_msg"] = "Error occured in Saving data";
      echo json_encode($response);
  }
}else if ($tag == 'CheckNumberOfEventsForUserToday'){
  // Request type is Register new user
  $userId = $_POST['userId'];
  $actualDate = $_POST['actualDate'];
  $uzivatel = $db->CheckNumberOfEventsForUserToday($userId,$actualDate);
  if ($uzivatel != false) {
    $response["error"] = FALSE;
    $response["event_idd"] = $uzivatel["event_idd"];
    echo json_encode($response);
  }else {
      // user not found
      // echo json with error = 1
      $response["error"] = TRUE;
      $response["error_msg"] = "Error occured in Saving data";
      echo json_encode($response);
  }
}else if ($tag == 'save_ProfilePicture'){
    // Request type is Register new user
    $email = $_POST['email'];
    $profilePicture = $_POST['profilePicture'];
    $uzivatel = $db->storeUserProfilePictureData($email,$profilePicture);
    if ($uzivatel != false) {
      $response["error"] = FALSE;
      $response["user_id"] = $uzivatel["user_id"];
      $response["user_name"] = $uzivatel["user_name"];
      $response["user_age"]= $uzivatel["user_age"];
      $response["user_weight"] = $uzivatel["user_weight"];
      $response["user_height"] = $uzivatel["user_height"];
      $response["user_sports"]= $uzivatel["user_sports"];
      $response["user_email"] = $uzivatel["user_email"];
      $response["user_profilePicture"] = $user["user_profilePicture"];
      echo json_encode($response);
    }else {
        // user not found
        // echo json with error = 1
        $response["error"] = TRUE;
        $response["error_msg"] = "Error occured in Saving data";
        echo json_encode($response);
    }
}else if ($tag == 'getCurrentEventInformation'){
    // Request type is Register new user
    $eventId = $_POST['eventId'];
    $uzivatel = $db->getCurrentEventInformationAll($eventId);
    if ($uzivatel != false) {
      $response["error"] = FALSE;
      $response["user_email"] = $uzivatel["user_email"];
      $response["event_name"] = $uzivatel["event_name"];
      $response["event_description"]= $uzivatel["event_description"];
      $response["event_numberOfPeople"] = $uzivatel["event_numberOfPeople"];
      $response["event_peopleJoined"] = $uzivatel["event_peopleJoined"];
      $response["event_address"] = $uzivatel["event_address"];
      $response["event_picture"]= $uzivatel["event_picture"];
      $response["event_date"] = $uzivatel["event_date"];
      $response["event_time"]= $uzivatel["event_time"];
      echo json_encode($response);
    }else {
        // user not found
        // echo json with error = 1
        $response["error"] = TRUE;
        $response["error_msg"] = "Error occured in Saving data";
        echo json_encode($response);
    }
}else if ($tag == 'countPeopleJoined'){
    // Request type is Register new user
    $eventId = $_POST['eventId'];
    $uzivatel = $db->getPeopleJoined($eventId);
    if ($uzivatel != false) {
      $response["error"] = FALSE;
      $response["countPeopleJoined"] = $uzivatel["countPeopleJoined"];
      echo json_encode($response);
    }else {
        // user not found
        // echo json with error = 1
        $response["error"] = TRUE;
        $response["error_msg"] = "Error occured in Saving data";
        echo json_encode($response);
    }
}else if ($tag == 'retreiveNotificationSettings'){
    // Request type is Register new user
    $userId = $_POST['userId'];
    $uzivatel = $db->retreiveNotificationSettings($userId);
    if ($uzivatel != false) {
      $response["error"] = FALSE;
      $response["user_sendNotOfCurrentDay"] = $uzivatel["user_sendNotOfCurrentDay"];
      $response["user_setNotOfAllJoined"] = $uzivatel["user_setNotOfAllJoined"];
      $response["user_sendNot"] = $uzivatel["user_sendNot"];
      echo json_encode($response);
    }else {
        // user not found
        // echo json with error = 1
        $response["error"] = TRUE;
        $response["error_msg"] = "Error occured in Saving data";
        echo json_encode($response);
    }
}else if ($tag == 'addCurrentUserToEvent'){
    // Request type is Register new user
    $eventId = $_POST['eventId'];
    $userId = $_POST['userId'];
    $uzivatel = $db->addCurrentUserToEvent($eventId,$userId);
    if ($uzivatel != false) {
      $response["error"] = FALSE;
      echo json_encode($response);
    }else {
        // user not found
        // echo json with error = 1
        $response["error"] = TRUE;
        $response["error_msg"] = "Error occured in Saving data";
        echo json_encode($response);
    }
}else if ($tag == 'NotficationsSettings'){
    // Request type is Register new user
    $user = $_POST['user'];
    $sendNotifications = $_POST['sendNotifications'];
    $sendNotificationsOfCurrentDay = $_POST['sendNotificationsOfCurrentDay'];
    $sendNotificationsOfAllJoined = $_POST['sendNotificationsOfAllJoined'];
    $uzivatel = $db->updateNotificationSettings($user,$sendNotifications,$sendNotificationsOfCurrentDay,$sendNotificationsOfAllJoined);
    if ($uzivatel != false) {
      $response["error"] = FALSE;
      echo json_encode($response);
    }else {
        // user not found
        // echo json with error = 1
        $response["error"] = TRUE;
        $response["error_msg"] = "Error occured in Saving data";
        echo json_encode($response);
    }
}else if ($tag == 'addPeopleJoined'){
    // Request type is Register new user
    $eventId = $_POST['eventId'];
    $peopleJoined = $_POST['peopleJoined'];
    $uzivatel = $db->addPeopleJoinedToEvents($eventId,$peopleJoined);
    if ($uzivatel != false) {
      $response["error"] = FALSE;
      echo json_encode($response);
    }else {
        // user not found
        // echo json with error = 1
        $response["error"] = TRUE;
        $response["error_msg"] = "Error occured in Saving data";
        echo json_encode($response);
    }
}else if ($tag == 'save_eventInformation'){
    // Request type is Register new user
    $email = $_POST['email'];
    $event_name = $_POST['eventName'];
    $event_description = $_POST['eventDescription'];
    $event_numberOfPeople = $_POST['eventNumberOfPeopleAllowed'];
    $event_address = $_POST['eventAddress'];
    $event_picture = $_POST['eventPicture'];
    $event_date = $_POST['eventDate'];
    $event_time = $_POST['eventTime'];
    $uzivatel = $db->storeEventData($email,$event_name,$event_description,$event_numberOfPeople,$event_address,$event_picture,$event_date,$event_time);
    if ($uzivatel != false) {
      $response["error"] = FALSE;
      $response["user_email"] = $uzivatel["user_email"];
      $response["event_id"] = $uzivatel["event_id"];
      $response["event_name"] = $uzivatel["event_name"];
      $response["event_description"]= $uzivatel["event_description"];
      $response["event_numberOfPeople"] = $uzivatel["event_numberOfPeople"];
      $response["event_address"] = $uzivatel["event_address"];
      $response["event_picture"]= $uzivatel["event_picture"];
      $response["event_date"] = $uzivatel["event_date"];
      $response["event_time"]= $uzivatel["event_time"];
      echo json_encode($response);
    }else {
        // user not found
        // echo json with error = 1
        $response["error"] = TRUE;
        $response["error_msg"] = "Error occured in Saving data";
        echo json_encode($response);
    }
}else if ($tag == 'register') {
        // Request type is Register new user
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $verification = $_POST['verification'];

        // check if user is already existed
        if ($db->isUserExisted($email)) {
            // user is already existed - error response
            $response["error"] = TRUE;
            $response["error_msg"] = "User already existed";
            echo json_encode($response);
        } else {
            // store user
            $user = $db->storeUser($name, $email, $password, $verification);
            if ($user) {
                // user stored successfully
                $response["error"] = FALSE;
                $response["uid"] = $user["user_id"];
                $response["user"]["name"] = $user["user_name"];
                $response["user"]["email"] = $user["user_email"];
                echo json_encode($response);
            } else {
                // user failed to store
                $response["error"] = TRUE;
                $response["error_msg"] = "Error occured in Registartion";
                echo json_encode($response);
            }
        }
    } else {
        // user failed to store
        $response["error"] = TRUE;
        $response["error_msg"] = "Unknown 'tag' value. It should be either 'login' or 'register'";
        echo json_encode($response);
    }
//} else if (isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['verification']) && !empty($_GET['verification'])) {
    // include DB_function
    //require_once 'DB_Functions.php';
    //$db = new DB_Functions();
    //$uzivatel = $db->checkVerification($email,$verification);
}else{
    ?><html>
    	<head>
    		<title>SportBuddy</title>
    		<meta charset="utf-8" />
    		<meta name="viewport" content="width=device-width, initial-scale=1" />
    		<link rel="stylesheet" href="assets/css/main.css" />
    	</head>
    	<body>
    			<section id="header">
    				<div class="inner">
    					<span class="icon major fa-futbol-o"></span>
    					<h1>
                <strong>SportBuddy</strong>
              </h1>
    					<p>Sophisticated</p>
              <p>Comprendious</p>
              <p>Easy to use</p>
    					<ul class="actions">
    						<li><a href="#one" class="button scrolly">Objav viac</a></li>
    					</ul>
    				</div>
    			</section>
    			<section id="one" class="main style1">
    				<div class="container">
    					<div class="row 150%">
    						<div class="6u 12u$(medium)">
    							<header class="major">
    								<h2>Čo je to SportBuddy?<br /></h2>
    							</header>
    							<p>Žijeme v unáhlenej dobe, rýchle občerstvenie, veľa práce a málo voľného času, každý sa niekam ponáhľa. Preto sa čoraz viacej dáva dôraz na zdravý životný štýl a hlavne športovanie.</p>
                  <p>SportBuddy je sofistikovaná Android aplikácia vyvinutá Denisom Trebulom ktorá umožňuje vytvárať športové udalosti a zároveň prihlasovať sa na ne, prostredníctvom tejto aplikácie môžete menežovať
                  športové akcie aj mimo sociálných sietí.</p>
    						</div>
    						<div class="6u$ 12u$(medium) important(medium)">
    							<span class="image fit"><img src="images/pic01.jpg" alt="" /></span>
    						</div>
    					</div>
    				</div>
    			</section>
    			<section id="two" class="main style2">
    				<div class="container">
    					<div class="row 150%">
    						<div class="6u 12u$(medium)">
    							<ul class="major-icons">
    								<li><span class="icon style1 major fa-mobile-phone"></span></li>
    								<li><span class="icon style2 major fa-android"></span></li>
    								<li><span class="icon style3 major fa-users"></span></li>
    								<li><span class="icon style4 major fa-futbol-o"></span></li>
    								<li><span class="icon style5 major fa-comments"></span></li>
    								<li><span class="icon style6 major fa-calendar"></span></li>
    							</ul>
    						</div>
    						<div class="6u$ 12u$(medium)">
    							<header class="major">
    								<h2>Spektrum možností</h2>
    							</header>
    							<p>Aplikácia sa dá jednoducho stiahnuť a nainštalovať do mobilného zariadenia pod operačným systémom Android vo verzii Jelly Bean(4.1) až po najnovšie verzie OS.</p>
    							<p>K ponuke máte možnosť vytvorenia vlastného profilu kde si zadáte základne údaje o sebe a vyberiete profilovú fotku vďaka ktorej vás môžu napríklad noví spoluhráči spoznať</p>
    							<p>Môžete sa stať organizátorom športovej udalosti, akcie alebo turnaja kde súťažiaci môžu byť odmenení za víťazstvo alebo sa pripojíte ku ktorejkoľvek udalosti ktorú si vyberiete, či už to bude
                    squash, tennis, hokej, futbal alebo rugby.</p>
                  <p>Vďaka športovým udalostiam založených v SportBuddy budete môcť spoznať nespočetne nových ľudí, priateľov, známych s ktorými v budúcnosti môžete vytvárať a organizovať udalosti alebo sa opätovne
                  k niektorej pridáte.</p>
    						</div>
    					</div>
    				</div>
    			</section>
    			<section id="three" class="main style1 special">
    				<div class="container">
    					<header class="major">
    						<h2>“The miracle isn't that I finished. The miracle is that I had the courage to start.”</h2>
    					</header>
    					<p>Spoznajte kopu nových ľudí, nadviažte kontakty, zašportujte si, v zdravom tele zdravý duch !</p>
    					<div class="row 150%">
    						<div class="4u 12u$(medium)">
    							<span class="image fit"><img src="images/pic02.jpg" alt="" /></span>
    							<h3>“I always wanted to be a basketball player. Nothing more, nothing less.”</h3>
    							<p>Objavte čaro basketbalu keď sa pridáte ku ktorejkoľvek basketbalovej udalosti vytvorenej prostredníctvom SportBuddy a získajte nové skúsenosti a zážitky.</p>
    							<ul class="actions">
    								<li><a href="http://elfsport.sk/clanok/majstrovstva-stu-v-basketbale-o-pohar-rektora-2013.html" class="button">Zisti viac</a></li>
    							</ul>
    						</div>
    						<div class="4u 12u$(medium)">
    							<span class="image fit"><img src="images/pic03.jpg" alt="" /></span>
    							<h3>“Even though you are competing, you still have a great fun.”</h3>
    							<p>Zistite aké je to skórovať dôležitý gól keď to tím potrebuje, ľudia vás sledujú, situácia je napätá, je to len na vás čo spravíte s tou elektrizujúcou atmosférou.</p>
    							<ul class="actions">
    								<li><a href="http://itfooscup.sk/" class="button">Zisti viac</a></li>
    							</ul>
    						</div>
    						<div class="4u$ 12u$(medium)">
    							<span class="image fit"><img src="images/pic04.jpg" alt="" /></span>
    							<h3>“Alone we can do so little, together we can do so much.”</h3>
    							<p>Založte s priateľmi udalosť alebo sa zúćastnite turnaja a užite si skvelo strávený čas prostredníctvom športu a volejbalu.</p>
    							<ul class="actions">
    								<li><a href="http://sport.upc.uniba.sk/turnaje/volejbalovy-turnaj-upcup-2016/" class="button">Zisti viac</a></li>
    							</ul>
    						</div>
    				</div>
            <div class="row 150%">
              <div class="4u 12u$(medium)">
                <span class="image fit"><img src="images/pic002.jpg" alt="" /></span>
                <h3>“Competition is always a good thing. It forces us to do our best.”</h3>
                <p>Zažite na vlastnú kožu známe 24 hodinové turnaje, siahnite na dno svojich fyzických možností a zistite čoho je vaše telo skutočne možné.</p>
                <ul class="actions">
                  <li><a href="http://www.volejbalkosicka.sk/index.php?page=24hours" class="button">Zisti viac</a></li>
                </ul>
              </div>
              <div class="4u 12u$(medium)">
                <span class="image fit"><img src="images/pic003.jpg" alt="" /></span>
                <h3>“You will never win if you never begin.”</h3>
                <p>Spoznajte aké je to zožať plody tvrdej driny, pridavajte sa pravidelne k udalostiam ktoré vás zaujímajú a odmena v podobe úspechu vás určite neminie.</p>
                <ul class="actions">
                  <li><a href="http://www.pragueblackpanthers.cz/" class="button">Zisti viac</a></li>
                </ul>
              </div>
              <div class="4u$ 12u$(medium)">
                <span class="image fit"><img src="images/pic004.jpg" alt="" /></span>
                <h3>“Challenging your colleagues is always a great fun.”</h3>
                <p>Zoberte kolegov z prace a pridajte sa prostrednictvom aplikácie na turnaj kde zažijete množstov zážitkov a možno aj úspechov.</p>
                <ul class="actions">
                  <li><a href="http://itfooscup.sk/" class="button">Zisti viac</a></li>
                </ul>
              </div>
          </div>
    			</section>
    			<section id="four" class="main style2">
    				<div class="container">
    					<div class="row 150%">
    						<div class="5u 12u$(medium)">
    						<ul class="major-icons">
    								<li><span class="image fit"><img src="images/pic009.png" alt="" /></span></li>
    							</ul>
    						</div>
    						<div class="6u$ 12u$(medium)">
    						</br>
    							<header class="major">
    								<h2>Využi jednoduchú možnosť registrácie a prihlasovania.</h2>
    							</header>
    							<p>Vytvor si účet iba za využitia tvojej emailovej adresy, žiadne potvrdzovacie e-maily, žiadne potvrdzovacie heslá, po registrovaní máš účet okamžite k dispozícii.</p></br>
    							<header class="major">
    								<h2>Dbáme na bezpečnosť.</h2>
    							</header>
    							<p>Na prihlásenie si potrebuješ pamätať svoje heslo a e-mailovú adresu ktorú si zadal pri registrácii, heslo môže byť akokoľvek dlhé a pritom stále bezpečné lebo aplikácia ho šifruje za pomoci
    							šifrovacích funkcií</p>
    							<p>Využívame najnovšie technológie androidu a zabezpečená komunikácia s databázou je našou garanciou ochrany súkromia.</p>
    						</div>
    					</div>
    				</div>
    			</section>
    			<section id="five" class="main style1">
    				<div class="container">
    					<div class="row 150%">
    						<div class="6u 12u$(medium)">
    							<header class="major">
    								<h2>Jednoduché a prehladné menu ! <br /></h2>
    							</header>
    							<p>Aplikácia obsahuje jednoduché a prehľadné menu v ktorom sa nemôžete stratiť.</p>
    						</br>
    						<p>Základom je jednoduchosť, user-friendly aplikácie dobývajú svet, na výber máte založenie vlastného profilu ktoré sa od vás očakáva, možnosť pracovať s udalostiami v sekcii events,
    						 zaslať správu vývojovému tímu prostredníctvom tlačidla help.</p>
    						</div>
    						<div class="4u$ 12u$(medium) important(medium)">
    							<span class="image fit"><img src="images/pic010.png" alt="" /></span>
    						</div>
    					</div>
    				</div>
    			</section>
    			<section id="six" class="main style2">
    				<div class="container">
    					<div class="row 150%">
    						<div class="5u 12u$(medium)">
    						<ul class="major-icons">
    								<li><span class="image fit"><img src="images/pic011.png" alt="" /></span></li>
    							</ul>
    						</div>
    						<div class="6u$ 12u$(medium)">
    						</br>
    							<header class="major">
    								<h2>Založ si vlastný profil ! <br /></h2>
    							</header>
    							<p>Využi možnosť založenia si vlastného profilu vďaka ktorému ťa noví spoluhráči rozoznajú, zadaj si tam svoje údaje ktoré budú môcť vidieť organizátori udalosti, vyplň si svoje preferované športy,
    							hmotnosť, výšku, vek a meno. Môžeš využiť možnosť vybrať si profilovú fotku z galérie vo svojom zariadení.</p>
    						</br>
    						<p>Strohý dizajn aplikácie bude v budúcnosti nahradení kvalitnejšiou grafikou a takisto aj funkcionalitou, nezabúdajte že toto je len Beta verzia aplikácie vyvíjanej jediným developerom,
    						aj napriek tomu sa môžete stať súčasťou športovej komunity a prispievať k organizovaniu športových udalostí ktoré spájajú ľudí.</p>
    						</div>
    						</div>
    					</div>
    				</div>
    			</section>
    			<section id="seven" class="main style1">
    				<div class="container">
    					<div class="row 150%">
    						<div class="6u 12u$(medium)">
    							<header class="major">
    								<h2>Vytváraj eventy ! <br /></h2>
    							</header>
    							<p>Využi možnosť založenia si vlastného eventu na ktorý sa ti môžu prihlásiť ľudia. Event môže predstavovať turnaj, udalosť alebo obyčajné stretnutie pri športe a následujúci teambuilding na
    								prehĺbenie vzťahov.</p>
    						<p>Vyberte obrázok ktorý bude reprezentovať vašu udalosť, nezabúdajte že vzhľad predáva a podľa toho si aj používateľ môže hneď všimnúť vašu udalosť. Zadajte názov
    							udalosti, môže to byť jednoduché "Malý futbal v telocvični Mladosť", alebo kludne komplexnejšie ako napríklad pre spomínane turnaje: "Streetball cup 2017".</p>
    						<p>Zadajte opis udalosti, ktorý môže predstavovať hocičo čo si k udalosti želáte dodať, napríklad čas konania a podobne.</p>
    						<p>Zadajte adresu kde sa bude udalosť odohrávať, ako skúšku správnosti môžete využiť tlačidlo "SHOW MAP" ktoré vam zobrazí na mape miesto udalosti, ktoré sa zobrazí
    							aj používateľom ktorý sa budú chcieť pridať k vašej udalosti.</p>
    						</div>
    						<div class="4u$ 12u$(medium) important(medium)">
    							<span class="image fit"><img src="images/pic012.png" alt="" /></span>
    						</div>
    					</div>
    				</div>
    			</section>
    			<section id="eight" class="main style2 special">
    				<div class="container">
    					<header class="major">
    						<h2>Na čo ešte čakáš?</h2>
    					</header>
    					<p>Stiahni si aplikáciu a spoznaj krásu kolektívnych športov.</p>
    					<ul class="actions uniform">
    						<li><a href="#" class="button special">Download</a></li>
    					</ul>
    				</div>
    			</section>
    			<section id="footer">
    				<ul class="icons">
    					<li><a href="https://www.facebook.com/denis.trebula" class="icon alt fa-facebook"><span class="label">Facebook</span></a></li>
    					<li><a href="https://www.instagram.com/explore/tags/sportbuddy/" class="icon alt fa-instagram"><span class="label">Instagram</span></a></li>
    					<li><a href="mailto:denistreb@gmail.com" class="icon alt fa-envelope"><span class="label">Email</span></a></li>
    				</ul>
    				<ul class="copyright">
    					<li>&copy; SportBuddy 2017</li>
    				</ul>
    			</section>
    			<script src="assets/js/jquery.min.js"></script>
    			<script src="assets/js/jquery.scrolly.min.js"></script>
    			<script src="assets/js/skel.min.js"></script>
    			<script src="assets/js/util.js"></script>
    			<script src="assets/js/main.js"></script>
    	</body>
    </html>
<?php
}
?>
