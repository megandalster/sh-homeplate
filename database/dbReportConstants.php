<?php
include_once(dirname(__FILE__).'/dbinfo.php');

function getConstants() {
    $con=connect();
    $query = <<<SQL
                SELECT name,
                       type,
                       value
                FROM reportConstants
SQL;
    $constants = [];
    $result = mysqli_query ($con,$query);
    while ($result_row = mysqli_fetch_assoc($result)) {
        if ($result_row['type'] == 'float')
            $constants[$result_row['name']] = floatval($result_row['value']);
        else if ($result_row['type'] == 'array')
            $constants[$result_row['name']] = explode('|',$result_row['value']);
        else
            error_log("Unknown Report Constants type: ".$result_row['type']);
    }
    mysqli_close($con);
    return $constants;
}

function setConstant($name, $value) {
    $con=connect();
    if (is_array($value)) {
        $value = implode('|', $value);
        error_log("writing: '".$value."'");
    }
    
    $query = <<<SQL
                UPDATE reportConstants
                    SET value='$value'
                WHERE
                    name='$name'
SQL;
    $result = mysqli_query ($con,$query);
    mysqli_close($con);
}

// selected is array of currently select Donors
function getOptionsForDonors($selected) {
    $con=connect();
    $in = implode("','",$selected);
    $query = <<<SQL
        SELECT c.id as id,
                IF (c2.id IS NOT NULL, 'true','false') AS selected
        FROM `dbClients` c
        LEFT JOIN dbClients c2 on c2.id=c.id
            AND c2.id IN ('$in')
        WHERE c.type='Donor'
            AND c.status='active'
            AND c.donor_type='Rescued Food'
        ORDER BY 1
SQL;
    $options = '';
    $result = mysqli_query ($con,$query);
    while ($result_row = mysqli_fetch_assoc($result)) {
        $id = $result_row['id'];
        $selected = $result_row['selected'] == 'true' ? 'SELECTED' : '';
        $options .= "<option value=\"$id\" $selected>&nbsp;$id</option>";
    }
    mysqli_close($con);
    return $options;
}
