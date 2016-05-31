<?php

/**
 * GET all beers
 * GET a specific beer by primary key
 * GET beers by brewery Id
 * GET beer by IBU
 * GET beer by beer color
 * GET beer by beer name
 * GET beer by style
 * POST create a brand new beer
 * PUT update beer
 * DELETE a beer
 **/
require_once dirname(dirname(_DIR_)) . "/classes/autoloader.php";
require_once dirname(dirname(_DIR_)) . "/lib/xsrf.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\BrewCrew\Beer;


/**
 * api for the beer class
 *
 * @author Arlene Carol Graham <agraham14@cnm.edu>
 */

//verify the session, start if not active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	//grab the mySQL connection
	$pdo = ConnectToEncryptedMySQL("/etc/apache2/capstone-mysql/brewcrew.ini");

	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] :$_SERVER["REQUEST_METHOD"];

	//Sanitize input
	$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

	//make sure the id is valid for methods that require it
	if(($method === "DELETE" || $method === "PUT") && (empty($id) === true || $id < 0)) {
		throw(new InvalidArgumentException("id cannot be empty or negative", 405));
	}

	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();

		$beerBreweryId = filter_input(INPUT_GET, "beerBreweryId", FILTER_VALIDATE_INT);
		$beerColor = filter_input(INPUT_GET, "beerColor", FILTER_SANITIZE_NUMBER_FLOAT);
		$beerIbu = filter_input(INPUT_GET, "beerIbu", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		$beerName = filter_input(INPUT_GET, "beerName", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		$beerStyle = filter_input(INPUT_GET, "beerStyle", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);


		//get a specific beer or all beers and update reply
		if(empty($id) === false) {
			$beer = Beer::getBeerByBeerId($pdo, $id);
			if($beer !== null) {
				$reply->data = $beer;
			}

		} else if(empty($beerBreweryId) === false) {
			$beer = Beer::getBeerByBeerBreweryId($pdo, $beerBreweryId);
			if($beer !== null) {
				$reply->$beer;
			}
		} else if(empty($beerIbu) === false) {
			$beer = Beer::getBeerByBeerIbu($pdo, $beerIbu);
			if($beer !== null) {
				$reply->$beer;
			}
		} else if(empty($beerColor) === false) {
			$beer = Beer::getBeerByBeerColor($pdo, $beerColor);
			if($beer !== null) {
				$reply->$beer;
			}
		} else if(empty($beerName) === false) {
			$beer = Beer::getBeerByBeerName($pdo, $beerName);
			if($beer !== null) {
				$reply->$beer;
			}
		} else if(empty($beerStyle) === false) {
			$beer = Beer::getBeerByBeerStyle($pdo, $beerStyle);
			if($beer !== null) {
				$reply->$beer;
			}
		} else {
			$beer = Beer::getAllBeers($pdo);
			$reply->data = $beers;
		}
	} else if($method === "PUT" || $method === "POST") {
		verifyXsrf();
		$requestBeerContent = file_get_contents("php://input");
		$requestBeerObject = json_decode($requestBeerContent);


		//perform the actual put or post

		if($method === "PUT") {

			$beer = Beer::getBeerByBeerId($pdo, $id);

			if($beer === null) {
				throw(new RuntimeException("Beer is not available", 404));
			}

			if($_SESSION["user"]->getUserAccessLevel() === 1 && $_SESSION["user"]->getUserBreweryId() === $beer->getBeerBreweryId()) {
// retrieve the beer by availability

// update beer by availability
				$beer->setBeerAvailabilty($requestObject->beerAvailability);
				$beer->setBeerDescription($requestObject->beerDescription);
				$beer->setBeerAwards($requestObject->beerAwards);
				$beer->update($pdo);
// update reply
				$reply->message = "Beer updated successfully";

				//perform the actual put or post


				if($method === "POST") {
// retrieve the beer to update
					$beer = Beer::getBeerByBeerBreweryId($pdo, $id);
					if($beer === null) {
						throw(new RuntimeException("Beer does not exist", 404));
					}
// put the new beer  into beer and update
					$beer->setBeerAvailability($requestBeerObject->beerAvailability);
					$beer->update($pdo);
// update reply
					$reply->message = "Beer updated successfully";

				}
			}
		}
	}
}
catch(Exception $exception) {

	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
	$reply->trace = $exception->getTraceAsString();

	header("Content-type: application/json");

	echo json_encode($reply);

} catch(\TypeError $typeError) {

	$reply->status = $typeError->getCode();
	$reply->message = $typeError->getMessage();
	$reply->trace = $typeError->getTraceAsString();

	header("Content-type: application/json");

	echo json_encode($reply);
}


