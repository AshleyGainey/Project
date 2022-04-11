<?php
session_start();


$method = "";
$loginSuccessful = false;
$registrationSuccessful = false;

require_once 'DBlogin.php';

$conn = new mysqli($host, $user, $pass, $database);
if ($conn->connect_error) die("Connection failed:" . $conn->connect_error);


function validMinLength($value, $minNumber)
{
    $length = strlen($value);
    $minValid = true;
    if ($length < $minNumber) {
        $minValid = false;
    }
    return $minValid;
}

function validMaxLength($value, $maxNumber)
{
    $length = strlen($value);
    $maxValid = true;
    if ($length > $maxNumber) {
        $maxValid = false;
    }
    return $maxValid;
}


function isStudentIDInDB($conn, $method)
{

    $stmt = $conn->prepare("SELECT UserID From user where UserID = ?");

    $username = $_POST['username'];

    $stmt->bind_param("s", $username);

    $stmt->execute();

    $res = $stmt->get_result();

    $row = $res->fetch_assoc();


    if ($res->num_rows > 0) {
        $stmt->close();
        return true;
    }

    $stmt->close();

    return false;
}

function isPasswordCorrect($username, $password, $conn)
{

    $stmt = $conn->prepare("SELECT UserID, FirstName From user where UserID = ? AND UserPassword = ?");

    $stmt->bind_param("ss", $username, $password);

    $stmt->execute();

    $result = $stmt->get_result();
    if (!$result) die($conn->error);

    $rows = $result->num_rows;


    if ($rows > 0) {
        for ($i = 0; $i < $rows; $i++) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $_SESSION['userID'] = $row["UserID"];
            $_SESSION['usersFirstName'] = $row["FirstName"];
            $stmt->close();
            return $_SESSION['userID'];
        }
    }

    $stmt->close();
    return false;
}


function isPasswordStrongEnough($password)
{
    $Result = "";

    if (strlen($password) < 8) {
        $Result = $Result . "The Password entered must contain at least 8 Characters" . "<br>";
    }
    if (strlen($password) > 99) {
        $Result = $Result . "The Password entered must contain Less Than 100 Characters" . "<br>";
    }
    if (!preg_match("#[0-9]+#", $password)) {
        $Result = $Result . "The Password entered must contain at least 1 Number" . "<br>";
    }
    if (!preg_match("#[A-Z]+#", $password)) {
        $Result = $Result . "The Password entered must contain at least 1 Uppercase Letter" . "<br>";
    }
    if (!preg_match("#[a-z]+#", $password)) {
        $Result = $Result . "The Password entered must contain at least 1 Lowercase Letter" . "<br>";
    }
    if (!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $password)) {
        $Result = $Result . "The Password entered must contain at least 1 Special Character" . "<br>";
    }
    return $Result;
}

//Login
if (isset($_POST['lSubmit'])) {


    $method = "Login";

    $loginSuccessful = false;
    $username = $_POST['username'];
    $password = $_POST['password'];

    $errorEmpty = false;
    $errorEmail = false;

    $emptyFields = [];


    //Username Length Checker
    $usernameHitsMinLength = validMinLength($username, 6);
    $usernameHitsMaxLength = validMaxLength($username, 6);

    $isIDInDB = isStudentIDInDB($conn, $method);


    if (empty($username) || empty($password)) {
        if (empty($username)) {
            array_push($emptyFields, "Username");
        }
        if (empty($password)) {
            array_push($emptyFields, "Password");
        }

        if (count($emptyFields) == 1) {
            $showFields = "field";
        } else if (count($emptyFields) > 1) {
            $showFields = "fields";
        }

        echo "<span class='errorInForm'>" . implode(", ", $emptyFields) . " " . $showFields . " cannot be left blank</span>";
        $errorEmpty = true;
    } //        Validate Username
    else if (!$usernameHitsMinLength || !$usernameHitsMaxLength || !ctype_digit($username) || !$isIDInDB) {
        if (!$usernameHitsMinLength || !$usernameHitsMaxLength) {
            $usernameMessage = "Please enter a 6 digit Student ID";
        } else if (!ctype_digit($username)) {
            $usernameMessage = "Please only enter digits only for Student ID.";
        } else {
            $usernameMessage = "There is no account addressed to this Student ID. Check that you have entered the correct ID or create an account.";
        }
        echo "<span class='errorInForm'>$usernameMessage</span>";
    } else if (!isPasswordCorrect($username, $password, $conn)) {
        echo "<span class='errorInForm'>Password is incorrect, make sure you have typed it in correctly.</span>";
    } else {
        $loginSuccessful = true;
        echo "<span class='successInForm'>Successfully Logged In, redirecting you to your account page</span>";
    }

    //    Register
} else if (isset($_POST['rSubmit'])) {


    echo "<span class='errorInForm'></span>";

    $method = "Register";
    $registrationSuccessful = false;

    $username = $_POST['username'];
    $password = $_POST['password'];

    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $emailAddress = $_POST['email'];


    $isIDInDB = isStudentIDInDB($conn, $method);

    $emptyFields = [];


    //Username Length Checker
    $usernameHitsMinLength = validMinLength($username, 6);
    $usernameHitsMaxLength = validMaxLength($username, 6);

    //    First Name Length Checker
    $firstNameHitsMaxLength = validMaxLength($firstName, 99);

    //    First Name Length Checker
    $lastNameHitsMaxLength = validMaxLength($lastName, 99);


    $passwordStrongEnough = isPasswordStrongEnough($password);

    //    echo "<span class='errorInForm'>Nah: $passwordStrongEnough</span>";


    //    Check if any are empty
    if (empty($username) || empty($password) || empty($firstName) || empty($lastName) || empty($emailAddress)) {
        if (empty($username)) {
            array_push($emptyFields, "Username");
        }
        if (empty($password)) {
            array_push($emptyFields, "Password");
        }
        if (empty($firstName)) {
            array_push($emptyFields, "First Name");
        }
        if (empty($lastName)) {
            array_push($emptyFields, "Last Name");
        }
        if (empty($emailAddress))
            array_push($emptyFields, "Email Address");


        if (count($emptyFields) == 1) {
            $showFields = "field";
        } else if (count($emptyFields) > 1) {
            $showFields = "fields";
        }

        echo "<span class='errorInForm'>" . implode(", ", $emptyFields) . " " . $showFields . " cannot be left blank</span>";


        $errorEmpty = true;
    } else if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
        echo "<span class='errorInForm'>Please enter a valid email address</span>";
        $errorEmail = true;
    } //        Validate Username
    else if (!$usernameHitsMinLength || !$usernameHitsMaxLength || !ctype_digit($username) || $isIDInDB) {
        if (!$usernameHitsMinLength || !$usernameHitsMaxLength) {
            $usernameMessage = "Please enter a 6 digit Student ID";
        } else if (!ctype_digit($username)) {
            $usernameMessage = "Please only enter digits only for Student ID.";
        } else {
            $usernameMessage = "There is already an account associated with Student ID, try logging in";
        }
        echo "<span class='errorInForm'>$usernameMessage</span>";
    } //       Validate Password
    else if ($passwordStrongEnough != "") {
        echo "<span class='errorInForm'>$passwordStrongEnough</span>";
    } //    Validate First Name
    else if (preg_match('~[0-9]~', $firstName)) {
        echo "<span class='errorInForm'>First Name cannot contain any digits</span>";
    } //    Validate Last Name
    else if (preg_match('~[0-9]~', $lastName)) {
        echo "<span class='errorInForm'>Last Name cannot contain any digits</span>";
    } //    Validate email address
    else if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
        echo "<span class='errorInForm'>Please enter a valid email address</span>";
        $errorEmail = true;
    } //    If no more validation Checks
    else {
        if (RegistrationSuccess($username, $password, $firstName, $lastName, $emailAddress, $conn)) {
            $registrationSuccessful = true;
            echo "<span class='successInForm'>Successfully Registered, redirecting you to your account page</span>";
        }
    }
} else {
    echo "There was an error with your request, please try again later";
}


function RegistrationSuccess($username, $password, $firstName, $lastName, $emailAddress, $conn)
{

    $stmt = $conn->prepare("INSERT INTO user (UserID, UserPassword, FirstName, LastName, EmailAddress)
VALUES (?, ?, ?, ?, ?)");


    $stmt->bind_param("sssss", $username, $password, $firstName, $lastName, $emailAddress);

    if ($stmt->execute()) {
        $_SESSION['userID'] = $username;
        $_SESSION['usersFirstName'] = $firstName;
        $stmt->close();
        return true;
    } else {
        echo "There was an error with your request to register, please try again later";
    }
    $stmt->close();


    return false;
}

?>

<script>
    var registrationSuccessful = "<?php echo $registrationSuccessful; ?>";

    var loginSuccessful = "<?php echo $loginSuccessful; ?>";

    //    If successful login or registration then redirect to account.php
    if (registrationSuccessful || loginSuccessful) {
        window.location.replace("account.php");
    }
</script>