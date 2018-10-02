<?php
// read sensor ID ('esp8266-'+ChipID)
$headers = array();
if (isset($_SERVER['HTTP_SENSOR']))
    $headers['Sensor'] = $_SERVER['HTTP_SENSOR'];
if (isset($_SERVER['HTTP_X_SENSOR']))
    $headers['Sensor'] = $_SERVER['HTTP_X_SENSOR'];

if (!isset($headers['Sensor'])) {
    die("no sensor id sended!");
}

// set the database parameters
$database_name = "luftdaten";
$database_host = "127.0.0.1";
$database_user = "change_it";
$database_password = "change_it";
$table_name = "sensor_data";

//establish the database connection
$pdo = new PDO('mysql:host=' . $database_host . ';dbname=' . $database_name, $database_user, $database_password);

//read the content sended to the API file
$json = file_get_contents('php://input');

// decode the encoded data into the results array
$results = json_decode($json, true);

// declare possible field names
$possible_fields = array("durP1", "ratioP1", "P1", "durP2", "ratioP2", "P2", "SDS_P1", "SDS_P2", "temperature", "humidity", "BMP_temperature", "BMP_pressure", "BME280_temperature", "BME280_humidity", "BME280_pressure", "samples", "min_micro", "max_micro", "signal");

// copy sensor data values to values array
foreach ($results["sensordatavalues"] as $sensordatavalues) {
    $values[$sensordatavalues["value_type"]] = $sensordatavalues["value"];
}
//set missing fields to ensure their presence
foreach ($possible_fields as $possible_field) {
    if (!isset($values[$possible_field])) {
        $values[$possible_field] = NULL;
    }
}
// prepare the database query
$insert = $pdo->prepare("REPLACE INTO `" . $database_name . "`.`" . $table_name . "`(`Time`,`SensorID`,`durP1`,`ratioP1`,`P1`,`durP2`,`ratioP2`,`P2`,`SDS_P1`,`SDS_P2`,`Temp`,`Humidity`,`BMP_temperature`,`BMP_pressure`,`BME280_temperature`,`BME280_humidity`,`BME280_pressure`,`Samples`,`Min_cycle`,`Max_cycle`,`Signal`) VALUES (NOW(),:SensorID,:durP1,:ratioP1,:P1,:durP2,:ratioP2,:P2,:SDS_P1,:SDS_P2,:Temp,:Humidity,:BMP_temperature,:BMP_pressure,:BME280_temperature,:BME280_humidity,:BME280_pressure,:Samples,:Min_cycle,:Max_cycle,:Signal)");
// execute the database query
$insert->execute(array(
    ':SensorID' => $headers['Sensor'],
    ':durP1' => $values["durP1"],
    ':ratioP1' => $values["ratioP1"],
    ':P1' => $values["P1"],
    ':durP2' => $values["durP2"],
    ':ratioP2' => $values["ratioP2"],
    ':P2' => $values["P2"],
    ':SDS_P1' => $values["SDS_P1"],
    ':SDS_P2' => $values["SDS_P2"],
    ':Temp' => $values["temperature"],
    ':Humidity' => $values["humidity"],
    ':BMP_temperature' => $values["BMP_temperature"],
    ':BMP_pressure' => $values["BMP_pressure"],
    ':BME280_temperature' => $values["BME280_temperature"],
    ':BME280_humidity' => $values["BME280_humidity"],
    ':BME280_pressure' => $values["BME280_pressure"],
    ':Samples' => $values["samples"],
    ':Min_cycle' => $values["min_micro"],
    ':Max_cycle' => $values["max_micro"],
    ':Signal' => $values["signal"]
));