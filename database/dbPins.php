<?php

include_once(dirname(__FILE__).'/../domain/Pin.php');
include_once(dirname(__FILE__).'/dbinfo.php');

// get select list
//  exclude any pins which are already pushed for the user
function pin_options_list($excluding_pins = []) {
    $exclude = [];
    foreach ($excluding_pins as $pin) {
        $exclude[$pin->get_pin_id()] = $pin->get_pin_id();
    }
    
    $options = '';
    $con=connect();
    $result = mysqli_query($con,
        "SELECT * FROM pins ORDER BY id");
    while($row = mysqli_fetch_assoc($result)) {
        if (!array_key_exists($row['id'],$exclude)) {
            $options .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
        }
    }
    mysqli_close($con);
    return $options;
}

// get pin list for $id
function fetch_pins($volunteer_id) {
    $con=connect();
    $pins = [];
    $result = mysqli_query($con,
        "SELECT vp.*,p.name FROM volunteer_pins vp
                JOIN pins p on p.id=vp.pin_id
                WHERE vp.volunteer_id='" . $volunteer_id . "' ORDER BY vp.pinned_date DESC");
    error_log("vid='$volunteer_id' : ".print_r($result,true));
    while($row = mysqli_fetch_assoc($result)) {
        $pins[] = new Pin(
            $row['id'],
            $row['volunteer_id'],
            $row['pin_id'],
            $row['pinned_date'],
            $row['name']
        );
    }
    mysqli_close($con);
    return $pins;
}


// add pin for id
function push_pin($pin) {
    $con=connect();
    $result = mysqli_query($con,
            "INSERT INTO volunteer_pins VALUES (null,'".
            $pin->get_volunteer_id()."'," .
            $pin->get_pin_id().",'" .
            $pin->get_pinned_date()."')"
    );
    if (!$result) {
        error_log("Unable to insert pin: ".mysqli_error($con));
        error_log("  Pin Data: ".print_r($pin,true));
    }
    mysqli_close($con);
}

// remove pin for id
function pull_pin($pin) {
    $con=connect();
    $result = mysqli_query($con,
        "DELETE FROM volunteer_pins WHERE id=".$pin->get_id()
    );
    if (!$result) {
        error_log("Unable to delete pin: ".mysqli_error($con));
        error_log("  Pin Data: ".print_r($pin,true));
    }
    mysqli_close($con);
}


?>