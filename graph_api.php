<?php
error_reporting(E_ALL);

$function = $_GET["query"];

class Credentials {
    function __construct(){
        $this->hostname = 'ec2-54-225-138-16.compute-1.amazonaws.com';
        $this->port = '5972';
        $this->dbName = 'd2br84lqj1ij30';
        $this->dbUser = 'u3l9t7fso9lsat';
        $this->dbPass = 'p7oqdm3h5jtre5180hhjsomls6f';
    }
}

function connect($creds){

  try{
        $dbObject = new PDO('pgsql:host='.$creds->hostname.";port=".$creds->port.";dbname=".$creds->dbName, $creds->dbUser, $creds->dbPass);

        $dbObject->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    }
     catch(PDOException $e){

        echo 'Connection failed... '.$e->getMessage();

        exit;
    
    }

  return $dbObject;

}

function getObservations(){

    $dataType = $_GET["dataType"];

    $people = $_GET["people"];

    $startDate = date("Y-m-d H:i:s", strtotime($_GET["startDate"]));

    $endDate = date("Y-m-d H:i:s", strtotime($_GET["endDate"]));

    $creds = new Credentials();

    $dbObject = connect($creds);

    $observerSearch = '';
    $peopleString = '';

    if($people){

      $peopleString = implode("','", $people);
      
      $observerSearch = " AND regexp_replace(unstable_observer.uri, '^.*/', '') IN ('{$peopleString}')";
    }
    $query =   "SELECT unstable_measurement.value AS temps,
                        unstable_observation.\"timestamp\" AS month 
                 FROM public.unstable_measurement
                 LEFT JOIN public.unstable_observation
                   ON unstable_measurement.observation_id = unstable_observation.id
                 LEFT JOIN public.unstable_observer
                   ON unstable_observation.observer_id = unstable_observer.id
                 WHERE unstable_observation.timestamp >= '{$startDate}' 
                   AND unstable_observation.timestamp <= '{$endDate}'".$observerSearch;

    $prepared = $dbObject->prepare($query);
    
    if(!$prepared){
      print "<p>DATABASE CONNECTION ERROR:</p>";
      print_r($dbObject->errorInfo());
      die;
    }

    //if($people){ $prepared->bindParam(':people', $peopleString); };
    // $prepared->bindParam(':startDate', $startDate);
    // $prepared->bindParam(':endDate', $endDate);

    $prepared->execute();

    $results = $prepared->fetchAll(PDO::FETCH_ASSOC);

     foreach($results as $entry){

       $entry['month'] = date("Y-m-d\TH:i:s", strtotime($entry['month']));
     }
    
    $result = json_encode($results);

    $dbObject = null;

    return $result;

}

header('Content-Type: application/json charset=utf-8');
echo $function();

?>