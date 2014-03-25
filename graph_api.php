<?php

error_reporting(E_ALL);

$function = $_GET["query"];

class Credentials {
    function __construct(){
        // Live setup
        $this->hostname = 'ec2-54-225-138-16.compute-1.amazonaws.com';
        $this->port = '5972';
        $this->dbName = 'd2br84lqj1ij30';
        $this->dbUser = 'u3l9t7fso9lsat';
        $this->dbPass = 'p7oqdm3h5jtre5180hhjsomls6f';
        // Local setup
        // $this->hostname = 'localhost';
        // $this->port = '5972';
        // $this->dbName = 'weatherblur';
        // $this->dbUser = 'u3l9t7fso9lsat';
        // $this->dbPass = 'p7oqdm3h5jtre5180hhjsomls6f';
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

    $dataType = $_GET["datatype"];

    $people = $_GET["people"];

    $startDate = date("Y-m-d H:i:s", strtotime($_GET["startDate"]));

    $endDate = date("Y-m-d H:i:s", strtotime($_GET["endDate"]));

    $creds = new Credentials();

    $dbObject = connect($creds);

    $observerSearch = '';
    $peopleString = '';

    if($people){

      $peopleString = implode("','", $people);
      
      $observerSearch = " AND regexp_replace(prod_observer.uri, '^.*/', '') IN ('{$peopleString}')";
    }
    $query =   "SELECT prod_measurement.value AS obsData,
                        prod_observation.\"timestamp\" AS month 
                 FROM public.prod_measurement
                 LEFT JOIN public.prod_observation
                   ON prod_measurement.observation_id = prod_observation.id
                 LEFT JOIN public.prod_observer
                   ON prod_observation.observer_id = prod_observer.id
                 WHERE prod_observation.timestamp >= '{$startDate}' 
                   AND prod_observation.timestamp <= '{$endDate}'
                   AND prod_measurement.phenomenon_id LIKE '{$dataType}'".$observerSearch;

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

function getHistoricalData(){

  $years = $_GET["years"];
  $city = $_GET["city"];
  $infoType =$_GET["infotype"];

  $referenceArray = array(
            "data_types" => array(
                  "maximum_air_temperature" => array("data_set" => "GHCNDMS", "data_type" => "MMXT"),
                  "average_air_temperature" => array("data_set" => "GHCNDMS", "data_type" => "MNTM"),
                  "minimum_air_temperature" => array("data_set" => "GHCNDMS", "data_type" => "MMNT"),
                  "average_precipitation" => array("data_set" => "GHCND", "data_type" => "PRCP")
              ),
            "stations" => array(
                  "portland" => array("station" =>"GHCND:USW00014764", "location" => "CITY:US230004"),
                  "sitka" => array("station" => "GHCND:USW00025333", "location" => "ZIP:99835"),
                  "rockland" => array("station" => "GHCND:USC00177250", "location" => "ZIP:04841")
              ),
            "token" => array("gUEuplBiMCvbOwYajpdAZfDbogBRCRCB", "bJNfxQOtaeCvSzrWDniQGSaMAFJyTeYW")
    );

    $fileString = "noaa_cache/".$city."-".$years."-".$infoType;

    


    if(file_exists($fileString) && file_get_contents($fileString) !== '[]'){

      $quickResponseString = file_get_contents($fileString);

      header("Connection: close");
      header("Content-Length: ".mb_strlen($quickResponseString));
      echo $quickResponseString;

    }

    // cache life set to 24 hours
    $cacheLife = 86400;

    $fileChanged = @filemtime($fileString);

    // Check if file has expired before running another query
    if(!$fileChanged or (time() - $fileChanged >= $cacheLife) or (file_get_contents($fileString) === '[]')){


      // set year intervals for number of queries to run based on datatype


      if($referenceArray["data_types"][$infoType]["data_type"] === "PRCP"){

        $queryCount = ($years+0);
        $queryInterval = 1;

        $queryLocationString = "&stationid=".$referenceArray["stations"][$city]["station"];

      }
      else{
        $queryCount = (($years+0)/10);
        $queryInterval = 10;

        $queryLocationString = "&locationid=".$referenceArray["stations"][$city]["location"];

      }

      // Check for historical anomalies for date ranges, like Rockland
      if($city === "rockland"){

        $currentDate = date("Y-m-d", strtotime("01 September 1976"));

      }
      else{
        $currentDate = date('Y-m-d');
      }
      
      $resultsArray = array();

      // Iterate through number of queries set, and write results to file
      for($i=1; $i<=$queryCount; $i++){

        //$firstDate = strtotime("-".($i*$queryInterval)." years", $currentDate);
        //$secondDate = strtotime("+".$queryInterval." years", $startDate);

        $firstDate = date_sub(date_create($currentDate), date_interval_create_from_date_string(($i*$queryInterval).' years'));
        $secondDate = date_sub(date_create($currentDate), date_interval_create_from_date_string((($i*$queryInterval)-$queryInterval).' years'));


        $startDate = date_format($firstDate, 'Y-m-d');
        $endDate = date_format($secondDate, 'Y-m-d');

        $requestURL = "http://www.ncdc.noaa.gov/cdo-web/api/v2/data?datasetid=".$referenceArray["data_types"][$infoType]["data_set"]
                          ."&datatypeid=".$referenceArray["data_types"][$infoType]["data_type"]
                          ."&startdate=".$startDate
                          ."&enddate=".$endDate
                          .$queryLocationString
                          ."&limit=366&includemetadata=false";
                          //die($requestURL);
        $curlCall = curl_init();
        curl_setopt($curlCall, CURLOPT_URL, $requestURL);
        curl_setopt($curlCall, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlCall, CURLOPT_HTTPHEADER, array("token: ".$referenceArray["token"][1]));

        usleep(200000);

        $rawResult =  curl_exec($curlCall);

        curl_close($curlCall);

        $decodedResult = json_decode($rawResult, true);

        foreach($decodedResult["results"] as $id => $value){
          $resultsArray[] = $value;
        }
        
      }

      $dataToWrite = json_encode($resultsArray);

      // Make sure to preserve cache from being overwritten with empty results
      if($dataToWrite === '[]'){

        return file_get_contents($fileString);
      
      }
      else{

        file_put_contents($fileString, $dataToWrite);

        return file_get_contents($fileString);

      }

    }
      
}

header('Content-Type: application/json charset=utf-8');
echo $function();

?>
