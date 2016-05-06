<?php

namespace Edu\Cnm\Agraham14\BrewCrew;
require_once("autoload.php");
header("Location: ..", true, 301);

class User implements \JsonSerializable {
	use ValidateDate;
	/**
	 * id for the User is the primary key
	 * @var int $userId
	 */
	private $userId;
	/**
	 * id for this userBreweryId; this is the foreign key
	 * @var int $userBreweryId
	 **/
	private $userBreweryId;
	/**
	 * user access level of 1 or 2
	 * @var int $userAccessLevel
	 */
	private $userAccessLevel;
	/**
	 * n userActivationToken
	 * @var int $userActivationToken
	 */
	private $userActivationToken;
	/**
	 * birth date of user
	 * @var \DateTime $userDateOfBirth
	 */
	private $userDateOfBirth;
	/**
	 * first name of user
	 * @var string $userFirstName
	 */
	private $userFirstName;
	/**
	 * email of user
	 * @var string $userEmail
	 */
	private $userEmail;
	/**
	 * name of userHash
	 * @var string $userHash
	 */
	private $userHash;
	/**
	 * last name of user
	 * @var string $userLastName
	 */
	private $userLastName;
	/**
	 * name of userSalt
	 * @var string $userSalt
	 */
	private $userSalt;
	/**
	 * username of user
	 * @var string $userUsername
	 */
	private $userUsername;

	/**
	 * constructor for User      *
	 * @param int|null $newUserId id of this User or null if a new User
	 * @param int $newUserBreweryId int id of the Brewery
	 * @param int $newUserAccessLevel
	 * @param int $newUserActivationToken int with user token
	 * @param \DateTime $newUserDateOfBirth date User was sent or null if set to current date and time
	 * @param string $newUserFirstName string containing actual user first name
	 * @param string $newUserEmail string containing user email
	 * @param string $newUserHash string containing actual user password hash
	 * @param string $newUserLastName string containing actual user LAST NAME
	 * @param string $newUserSalt string containing actual user password salt
	 * @param string $newUserUsername string containing actual user name
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long,
	 * negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 **/
	public function __construct (int $newUserId = null, int $newUserBreweryId, int $newUserAccessLevel, int $newUserActivationToken, $newUserDateOfBirth = null, string $newUserFirstName, string $newUserLastName, string $newUserEmail, string $newUserUsername, string $newUserHash, string $newUserSalt) {
		try {
			$this->setUserId($newUserId);
			$this->setUserBreweryId($newUserBreweryId);
			$this->setUserAccessLevel($newUserAccessLevel);
			$this->setUserActivationToken($newUserActivationToken);
			$this->setUserDateOfBirth($newUserDateOfBirth);
			$this->setUserFirstName($newUserFirstName);
			$this->setUserEmail($newUserEmail);
			$this->setUserHash($newUserHash);
			$this->setUserLastName($newUserLastName);
			$this->setUserSalt($newUserSalt);
			$this->setUserUsername($newUserUsername);
		} catch(\InvalidArgumentException $invalidArgument) {
			// rethrow the exception to the caller
			throw(new \InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(\RangeException $range) {
			// rethrow the exception to the caller
			throw(new \RangeException($range->getMessage(), 0, $range));
		} catch(\TypeError $typeError) {
			// rethrow the exception to the caller
			throw(new \TypeError($typeError->getMessage(), 0, $typeError));
		} catch(\Exception $exception) {
			// rethrow the exception to the caller
			throw(new \Exception($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * gets user by userBreweryId
	 * @param string $userBreweryId
	 * @return \SplFixedArray SplFixedArray of users by user brewery id found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getUserByUserBreweryId (\PDO $pdo, string $userBreweryId) {
		// sanitize the description before searching
		$userBreweryId = trim($userBreweryId);
		$userBreweryId = filter_var($userBreweryId, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($userBreweryId) === true) {
			throw(new \PDOException("user brewery id is invalid"));
		}
		// create query template
		$query = "SELECT userId, userBreweryId, userAccessLevel, userActivationToken, userDateOfBirth, userFirstName, userHash, userLastName, userSalt, userUsername,  FROM userBreweryId WHERE userBreweryId LIKE :userBreweryId";
		$statement = $pdo->prepare($query);

		//bind the userBreweryID to the place holder in the template
		$userBreweryId = "%$userBreweryId%";
		$parameters = array("userBreweryId" => $userBreweryId);
		$statement->execute($parameters);

		// build an array of users
		$users = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$user = new User($row["userId"], $row["userBreweryId"], $row["userAccessLevel"], $row["userActivationToken"], $row["userDateOfBirth"], $row["userEmail"], $row["userFirstName"], $row["userHash"], $row["userLastName"], $row["userSalt"], $row["userUsername"]);
				$users[$users->key()] = $user;
				$user->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($users);
	}

	/**
	 * gets user by userAccessLevel
	 *
	 * @param int $userAccessLevel
	 * @return \SplFixedArray SplFixedArray of users found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getUserByAccessLevel (\PDO $pdo, string $userAccessLevel) {
		// sanitize the description before searching
		$userAccessLevel = trim($userAccessLevel);
		$userAccessLevel = filter_var($userAccessLevel, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($uuserAccessLevel) === true) {
			throw(new \PDOException("user access level is invalid"));
		}
		// create query template
		$query = "SELECT userId, userBreweryId, userAccessLevel, userActivationToken, userDateOfBirth, userFirstName, userHash, userLastName, userSalt, userUsername,  FROM userAccessLevel WHERE userAccessLevel LIKE :userAccessLevel";
		$statement = $pdo->prepare($query);
		//bind the user access level to the place holder in the template
		$userAccessLevel = "%$userAccessLevel%";
		$parameters = array("userAccessLevel" => $userAccessLevel);
		$statement->execute($parameters);
		// build an array of users
		$users = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$user = new User($row["userId"], $row["userBreweryId"], $row["userAccessLevel"], $row["userActivationToken"], $row["userDateOfBirth"], $row["userEmail"], $row["userFirstName"], $row["userHash"], $row["userLastName"], $row["userSalt"], $row["userUsername"]);
				$users[$users->key()] = $user;
				$user->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($users);
	}

	/**
	 * gets user by userActivationToken
	 *
	 * @param string $userActivationToken user batting to search for
	 * @return \SplFixedArray SplFixedArray of users found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getUserByActivationToken (\PDO $pdo, string $userActivationToken) {
		// sanitize the description before searching
		$userActivationToken = trim($userActivationToken);
		$userActivationToken = filter_var($userActivationToken, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($userActivationToken) === true) {
			throw(new \PDOException("user activation token is invalid"));
		}
		// create query template
		$query = "SELECT userId, userBreweryId, userAccessLevel, userActivationToken, userDateOfBirth, userFirstName, userHash, userLastName, userSalt, userUsername,  FROM userActivationToken WHERE userActivationToken LIKE :userActivationToken";
		$statement = $pdo->prepare($query);
		//bind the user ActivationToken to the place holder in the template
		$userActivationToken = "%$userActivationToken%";
		$parameters = array("userActivationToken" => $userActivationToken);
		$statement->execute($parameters);
		// build an array of users
		$users = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$user = new User($row["userId"], $row["userBreweryId"], $row["userAccessLevel"], $row["userActivationToken"], $row["userDateOfBirth"], $row["userEmail"], $row["userFirstName"], $row["userHash"], $row["userLastName"], $row["userSalt"], $row["userUsername"]);
				$users[$users->key()] = $user;
				$user->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($users);
	}

	/**
	 * gets user by userDateOfBirth
	 *
	 * @param \DateTime $userDateOfBirth
	 * @return \SplFixedArray SplFixedArray of users found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getUserByUserDateOfBirth (\PDO $pdo, string $userDateOfBirth) {
		// sanitize the description before searching
		$userDateOfBirth = trim($userDateOfBirth);
		$userDateOfBirth = filter_var($userDateOfBirth, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($userDateOfBirth) === true) {
			throw(new \PDOException("user date of birth is invalid"));
		}
		// create query template
		$query = "SELECT userId, userBreweryId, userAccessLevel, userActivationToken, userDateOfBirth, userFirstName, userHash, userLastName, userSalt, userUsername,  FROM userBreweryId WHERE userBreweryId LIKE :userBreweryId";
		$statement = $pdo->prepare($query);
		//bind the user  date of birth to the place holder in the template
		$userDateOfBirth = "%$userDateOfBirth%";
		$parameters = array("userDateOfBirth" => $userDateOfBirth);
		$statement->execute($parameters);
		// build an array of users
		$users = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$user = new User($row["userId"], $row["userBreweryId"], $row["userAccessLevel"], $row["userActivationToken"], $row["userDateOfBirth"], $row["userEmail"], $row["userFirstName"], $row["userHash"], $row["userLastName"], $row["userSalt"], $row["userUsername"]);
				$users[$users->key()] = $user;
				$user->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($users);
	}

	/**
	 * gets user by userEmail
	 *
	 * @param varchar $userEmail
	 * @return \SplFixedArray SplFixedArray of users found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getUserByUserEmail (\PDO $pdo, string $userEmail) {
		// sanitize the description before searching
		$userEmail = trim($userEmail);
		$userEmail = filter_var($userEmail, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($userEmail) === true) {
			throw(new \PDOException("user email is invalid"));
		}
		// create query template
		$query = "SELECT userId, userBreweryId, userAccessLevel, userActivationToken, userDateOfBirth, userFirstName, userHash, userLastName, userSalt, userUsername,  FROM userEmail WHERE userEmail LIKE :userEmail";
		$statement = $pdo->prepare($query);
		//bind the user access level to the place holder in the template
		$userEmail = "%$userEmail%";
		$parameters = array("userEmail" => $userEmail);
		$statement->execute($parameters);
		// build an array of users
		$users = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$user = new User($row["userId"], $row["userBreweryId"], $row["userAccessLevel"], $row["userActivationToken"], $row["userDateOfBirth"], $row["userEmail"], $row["userFirstName"], $row["userHash"], $row["userLastName"], $row["userSalt"], $row["userUsername"]);
				$users[$users->key()] = $user;
				$user->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($users);
	}

	/**
	 * gets user by userFirstName
	 *
	 * @param string $userFirstName
	 * @return \SplFixedArray SplFixedArray of users found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getUserByuserFirstName (\PDO $pdo, string $userFirstName) {
		// sanitize the description before searching
		$userFirstName = trim($userFirstName);
		$userFirstName = filter_var($userFirstName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($userFirstName) === true) {
			throw(new \PDOException("user first name is invalid"));
		}
		// create query template
		$query = "SELECT userId, userBreweryId, userAccessLevel, userActivationToken, userDateOfBirth, userFirstName, userHash, userLastName, userSalt, userUsername,  FROM userFirstName WHERE userFirstName LIKE :userFirstName";
		$statement = $pdo->prepare($query);
		//bind the user first name to the place holder in the template
		$userFirstName = "%$userFirstName%";
		$parameters = array("userFirstName" => $userFirstName);
		$statement->execute($parameters);
		// build an array of users
		$users = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$user = new User($row["userId"], $row["userBreweryId"], $row["userAccessLevel"], $row["userActivationToken"], $row["userDateOfBirth"], $row["userEmail"], $row["userFirstName"], $row["userHash"], $row["userLastName"], $row["userSalt"], $row["userUsername"]);
				$users[$users->key()] = $user;
				$user->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($users);
	}

	/**
	 * gets user by userLastName
	 *
	 * @param string $userLastName
	 * @return \SplFixedArray SplFixedArray of users found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getUserByUserLastName (\PDO $pdo, string $userLastName) {
		// sanitize the description before searching
		$userLastName = trim($userLastName);
		$userLastName = filter_var($userLastName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($userLastName) === true) {
			throw(new \PDOException("user last name is invalid"));
		}
		// create query template
		$query = "SELECT userId, userBreweryId, userAccessLevel, userActivationToken, userDateOfBirth, userFirstName, userHash, userLastName, userSalt, userUsername,  FROM userLastName WHERE userLastName LIKE :userLastName";
		$statement = $pdo->prepare($query);
		//bind the user last name to the place holder in the template
		$userLastName = "%$userLastName%";
		$parameters = array("userLastName" => $userLastName);
		$statement->execute($parameters);
		// build an array of users
		$users = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$user = new User($row["userId"], $row["userBreweryId"], $row["userAccessLevel"], $row["userActivationToken"], $row["userDateOfBirth"], $row["userEmail"], $row["userFirstName"], $row["userHash"], $row["userLastName"], $row["userSalt"], $row["userUsername"]);
				$users[$users->key()] = $user;
				$user->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($users);
	}

	/**
	 * gets user by userUsername
	 *
	 * @param string $userUsername
	 * @return \SplFixedArray SplFixedArray of users found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getUserByUserUsername (\PDO $pdo, string $userUserName) {
		// sanitize the description before searching
		$userUserName = trim($userUserName);
		$userUserName = filter_var($userUserName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($userUserName) === true) {
			throw(new \PDOException("user name is invalid"));
		}
		// create query template
		$query = "SELECT userId, userBreweryId, userAccessLevel, userActivationToken, userDateOfBirth, userFirstName, userHash, userLastName, userSalt, userUsername,  FROM userUserName WHERE userUserNameLIKE :userUserName";
		$statement = $pdo->prepare($query);
		//bind the username to the place holder in the template
		$userUserName = "%$userUserName%";
		$parameters = array("userUserName" => $userUserName);
		$statement->execute($parameters);
		// build an array of users
		$users = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$user = new User($row["userId"], $row["userBreweryId"], $row["userAccessLevel"], $row["userActivationToken"], $row["userDateOfBirth"], $row["userEmail"], $row["userFirstName"], $row["userHash"], $row["userLastName"], $row["userSalt"], $row["userUsername"]);
				$users[$users->key()] = $user;
				$user->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($users);
	}

	/**
	 * gets the user by userId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $userId user id to search for
	 * @return user|null user found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getUserByUserId (\PDO $pdo, int $userId) {
		// sanitixe the user id before searching
		if($userId <= 0) {
			throw(new \PDOException("user id is not positive"));
		}
		// create query template
		$query = "SELECT userId, userBreweryId, userAccessLevel, userActivationToken, userDateOfBirth, userFirstName, userHash, userLastName, userSalt, userUsername,   FROM user WHERE userId = :userId";
		$statement = $pdo->prepare($query);
		// bind the user id to the place holder in the template
		$parameters = array("userId" => $userId);
		$statement->execute($parameters);
		// grab the user from mySQL
		try {
			$user = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$user = new User($row["userId"], $row["userBreweryId"], $row["userAccessLevel"], $row["userActivationToken"], $row["userDateOfBirth"], $row["userEmail"], $row["userFirstName"], $row["userHash"], $row["userLastName"], $row["userSalt"],
					$row["userUsername"]);
			}
		} catch(\Exception $exception) {
			// if the row couldnt be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($user);
	}

	/**
	 * accessor method for user id
	 *
	 * @return int|null value of user id
	 **/
	public function getUserId () {
		return ($this->userId);
	}

	/**
	 * mutator method for user id
	 *
	 * @param int|null $newUserId new value of user id
	 * @throws \RangeException if $newUserId is not positive
	 * @throws \TypeError if $newUserId is not an integer
	 **/
	public function setUserId (int $newUserId = null) {
		// base case: if the user id is null, this a new user without a mySQL assigned id (yet)
		if($newUserId === null) {
			$this->userId = null;
			return;
		}

		// verify the user id is positive
		if($newUserId <= 0) {
			throw(new \RangeException("user id must a positive number."));
		}

		// convert and store the user id
		$this->userId = intval($newUserId);
	}

	/**
	 * accessor method for User Brewery Id
	 *
	 * @return int|null value of user brewery id
	 **/
	public function getUserBreweryId () {
		return ($this->userBreweryId);
	}

	/**
	 * mutator method for user brewery id
	 *
	 * @param int|null $newUserBreweryId new value of user brewery id
	 * @throws \RangeException if $newUserBreweryId is not positive
	 * @throws \TypeError if $newUserBreweryId is not an integer
	 **/
	public function setUserBreweryId (int $newUserBreweryId = null) {

		if($newUserBreweryId === null) {
			$this->userBreweryId = null;
			return;
		}

		// verify the user brewery id is positive
		if($newUserBreweryId <= 0) {
			throw(new \RangeException("user brewery id must a positive number."));
		}

		// convert and store the user brewery id
		$this->userBreweryId = intval($newUserBreweryId);
	}

	/**
	 * accessor method for user AccessLevel
	 *
	 * @return int|null value of user Access Level
	 **/
	public function getUserAccessLevel () {
		return ($this->userAccessLevel);
	}

	/**
	 * mutator method for userAccessLevel
	 *
	 * @param int|null $userAccessLevel new value of user id
	 * @throws \RangeException if $userAccessLevel is not positive
	 * @throws \TypeError if $userAccessLevel is not an integer
	 **/
	public function setUserAccessLevel (int $userAccessLevel = null) {

		if($userAccessLevel === null) {
			$this->userAccessLevel = null;
			return;
		}
	}

	/**
	 * accessor method for userDateOfBirth date
	 *
	 * @return \DateTime value of userDateOfBirth date
	 **/
	public function getUserDateOfBirth () {
		return ($this->userDateOfBirth);
	}

	/**
	 * mutator method for userDateOfBirth date
	 *
	 * @param \DateTime|string|null $newUserDateOfBirth user DateOfBirth date as a DateTime object or string
	 * @throws \InvalidArgumentException if $newUserDateOfBirth is not a valid object or string
	 * @throws \RangeException if $newUserDateOfBirth is a date that does not exist
	 * @throws \OutOfRangeException if $newUserDateOfBirth is < 21
	 **/
	public function setUserDateOfBirth ($newUserDateOfBirth = null) {
		// base case: if the date is null, ask user to enter date of birth
		if($newUserDateOfBirth === null) {
			throw (new \OutOfBoundsException("You must enter your date of birth"));
		}
		$newUserDateOfBirth->add(new \DateInterval('y21'));
		if($newUserDateOfBirth > $newUserDateOfBirth . getdate()) {
			throw (new \RangeException("You are too young."));
		}
		// store the userDateOfBirth date
		$this->userDateOfBirth = date($newUserDateOfBirth);

	}

	/**
	 * accessor method for userActivationToken
	 * @return int|null value of userActivationToken
	 **/
	public function getUserActivationToken () {
		return ($this->userActivationToken);
	}

	/**
	 * mutator method for userActivationToken id
	 *
	 * @param int|null $newUserActivationToken new value of userActivationToken
	 * @throws \RangeException if $newUserActivationToken is not positive
	 * @throws \TypeError if $newUserActivationToken is not an integer
	 **/
	public function setUserActivationToken (int $newUserActivationToken = null) {
		// base case: if the userActivationToken id is null, this a new userActivationToken without a mySQL assigned id (yet)
		if($newUserActivationToken === null) {
			$this->userActivationTokenId = null;
			return;
		}

		// verify the userActivationToken id is positive
		if($newUserActivationToken <= 0) {
			throw(new \RangeException("UserActivationToken must a positive number."));
		}

		// convert and store the userActivationToken
		$this->userActivationToken = intval($newUserActivationToken);
	}

	/**
	 * accessor method for userFirstName
	 * @return string value of userFirstName
	 **/
	public function getUserFirstName () {
		return ($this->userFirstName);
	}

	/**
	 * mutator method for UserFirstName
	 *
	 * @param string $newUserFirstName new value of UserFirstName
	 * @throws \InvalidArgumentException if $newUserFirstNameis not a string or insecure
	 * @throws \RangeException if $newUserFirstName is > 32 characters
	 * @throws \TypeError if $newUserFirstName is not a string
	 **/
	public function setUserFirstName (string $newUserFirstName) {
		// verify the User's First Name content is secure
		$newUserFirstName = trim($newUserFirstName);
		$newUserFirstName = filter_var($newUserFirstName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newUserFirstName) === true) {
			throw(new \InvalidArgumentException("User First Name content is empty or insecure"));
		}

		// verify the first name content will fit in the database
		if(strlen($newUserFirstName) > 32) {
			throw(new \RangeException("First name content too large"));
		}

		// store the user's first name
		$this->userFirstName = $newUserFirstName;
	}

	/**
	 * accessor method for userLastName
	 * @return string value of userLastName
	 **/
	public function getUserLastName () {
		return ($this->userLastName);
	}

	/**
	 * mutator method for UserLastName
	 *
	 * @param string $newUserLastName new value of UserLastName
	 * @throws \InvalidArgumentException if $newUserLastNamei s not a string or insecure
	 * @throws \RangeException if $newUserLastName is > 32 characters
	 * @throws \TypeError if $newUserLastName is not a string
	 **/
	public function setUserLastName (string $newUserLastName) {
		// verify the User's Last Name content is secure
		$newUserLastName = trim($newUserLastName);
		$newUserLastName = filter_var($newUserLastName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newUserLastName) === true) {
			throw(new \InvalidArgumentException("User Last Name content is empty or insecure"));
		}

		// verify the last name content will fit in the database
		if(strlen($newUserLastName) > 32) {
			throw(new \RangeException("Last name content too large"));
		}

		// store the user's last name
		$this->userLastName = $newUserLastName;
	}

	/**
	 * accessor method for UserEmail
	 * @return string value of UserEmail
	 **/
	public function getUserEmail () {
		return ($this->userEmail);
	}

	/**
	 * mutator method for UserEmail
	 *
	 * @param string $newUserEmail new value of userEmail
	 * @throws \InvalidArgumentException if $newUserEmaili s not a string or insecure
	 * @throws \RangeException if $newUserEmail is > 128 characters
	 * @throws \TypeError if $newUserEmail is not a string
	 **/
	public function setUserEmail (string $newUserEmail) {
		// verify the User's email content is secure
		$newUserEmail = trim($newUserEmail);
		$newUserEmail = filter_var($newUserEmail, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newUserEmail) === true) {
			throw(new \InvalidArgumentException("User's email content is empty or insecure"));
		}

		// verify the email will fit in the database
		if(strlen($newUserEmail) > 128) {
			throw(new \RangeException("Email too large"));
		}

		// store the user's email
		$this->UserEmail = $newUserEmail;
	}

	/**
	 * accessor method for username
	 * @return string value of username
	 **/
	public function getUserUsername () {
		return ($this->userUsername);
	}

	/**
	 * mutator method for username
	 *
	 * @param string $newUserUsername new value of userUsername
	 * @throws \InvalidArgumentException if $newUsername is not a string or insecure
	 * @throws \RangeException if $newUsername is > 32 characters
	 * @throws \TypeError if $newUsername is not a string
	 **/
	public function setUserUsername (string $newUserUsername) {
		// verify the User's username is secure
		$newUserUsername = trim($newUserUsername);
		$newUserUsername = filter_var($newUserUsername, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newUserUsername) === true) {
			throw(new \InvalidArgumentException("Username is empty or insecure"));
		}

		// verify the username will fit in the database
		if(strlen($newUserUsername) > 32) {
			throw(new \RangeException("Username too large"));
		}

		// store the user's username
		$this->userUsername = $newUserUsername;
	}

	/**
	 * accessor method for userHash
	 * @return string value of userHash
	 **/
	public function getUserHash () {
		return ($this->userHash);
	}

	/**
	 * mutator method for userHash
	 *
	 * @param string $newUserHash new value of userHash
	 * @throws \InvalidArgumentException if $newUserHash is not a string or insecure
	 * @throws \RangeException if $newUserHash is > 64 characters
	 * @throws \TypeError if $newUserHash is not a string
	 **/
	public function setUserHash (string $newUserHash) {
		// verify the User's password hash is secure
		$newUserHash = trim($newUserHash);
		$newUserHash = filter_var($newUserHash, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newUserHash) === true) {
			throw(new \InvalidArgumentException("User's password hash is empty or insecure"));
		}

		// verify the hash will fit in the database
		if(strlen($newUserHash) > 64) {
			throw(new \RangeException("Hash too large"));
		}

		// store the userHash
		$this->userHash = $newUserHash;
	}

	/**
	 * accessor method for userSalt
	 * @return string value of userSalt
	 **/
	public function getUserSalt () {
		return ($this->userSalt);
	}

	/**
	 * mutator method for userSalt
	 *
	 * @param string $newUserSalt new value of userSalt
	 * @throws \InvalidArgumentException if $newUserSalt is not a string or insecure
	 * @throws \RangeException if $newUserSalt is > 64 characters
	 * @throws \TypeError if $newUserSalt is not a string
	 **/
	public function setUserSalt (string $newUserSalt) {
		// verify the User's password salt is secure
		$newUserSalt = trim($newUserSalt);
		$newUserSalt = filter_var($newUserSalt, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newUserSalt) === true) {
			throw(new \InvalidArgumentException("User's password salt is empty or insecure"));
		}

		// verify the salt will fit in the database
		if(strlen($newUserSalt) > 64) {
			throw(new \RangeException("Salt too large"));
		}

		// store the userSalt
		$this->userSalt = $newUserSalt;
	}

	/**
	 * @return array
	 */
	public function jsonSerialize () {
		$fields = get_object_vars($this);
		return ($fields);
	}
}
