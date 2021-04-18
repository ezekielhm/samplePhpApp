<?php

defined('SITE_NAME') OR exit('access denied');

class Task extends Controller
{
	public function index()
	{	

		$responseArr = [];

		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "tasks";

		try {
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
			  // set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt  = $conn->prepare("SELECT `task`,`id` FROM `tasks_table`");

			$stmt ->execute();

			$result = $stmt ->setFetchMode(PDO::FETCH_ASSOC); 
			foreach ($stmt->fetchAll() as $index => $tasks){
				array_push($responseArr,["task"=>$tasks['task'], "id"=>$tasks['id']]);
			}
		} catch(PDOException $e) {
		  echo "Connection failed: " . $e->getMessage();
		}

		$conn = null;
		

		$jsonres = [
			'response' => $responseArr,
			'code' => 200
		];

		echo json_encode($jsonres);
	}





	public function addTask()
	{
		$requestBody = file_get_contents('php://input');

		$jsonDecode = json_decode($requestBody, true);


		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "tasks";

		try {
			  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
			  // set the PDO error mode to exception
			  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			  // prepare sql and bind parameters
			  $stmt = $conn->prepare("INSERT INTO `tasks_table`(`task`) VALUES (:task)");
			  $stmt->bindParam(':task', $jsonDecode['task']);
			  // insert a row
			  $stmt->execute();	

			  $stmt = $conn->prepare("SELECT id FROM tasks_table ORDER BY id DESC LIMIT 1 ");
			  // insert a row
			  $stmt->execute();	

			  $result = $stmt ->setFetchMode(PDO::FETCH_ASSOC); 
				foreach ($stmt->fetchAll() as $tasksId){
					$LatestTaskId = $tasksId;
				}

				$infoJson = [
					"latestId" => $LatestTaskId['id'],
					"msg" => "New records created successfully"
				];

			  	$jsonres = [
					'response' => $infoJson,
					'code' => 200
				];

			  echo json_encode($jsonres);

		} catch(PDOException $e) {
		  echo "Error: " . $e->getMessage();
		}
		$conn = null;
	}



	public function delete()
	{
		$requestBody = file_get_contents('php://input');

		$jsonDecode = json_decode($requestBody, true);

		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "tasks";

		try {
			  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
			  // set the PDO error mode to exception
			  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			  // prepare sql and bind parameters
			  $stmt = $conn->prepare("DELETE FROM `tasks_table` WHERE id = :id");
			  $stmt->bindParam(':id', $jsonDecode['taskId']);
			  // insert a row
			  $stmt->execute();	

				$infoJson = [
					"deletedId" => $jsonDecode['taskId'],
					"msg" => "record deleted"
				];

			  	$jsonres = [
					'response' => $infoJson,
					'code' => 200
				];

			  echo json_encode($jsonres);

		} catch(PDOException $e) {
		 	echo "Error: " . $e->getMessage();
		}
		$conn = null;
	}
}