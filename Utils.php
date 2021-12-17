<?php

// showData: format date within heredoc
function showDate($date) {
    if ($date != '') {
        return date('m/d/Y', strtotime($date));
    }
    return '';
}
// selected: return SELECTED if matching
function selected($test,$answer) {
    return $test == $answer ? 'SELECTED' : '';
}

// $fn: call function within heredoc
global $fn;
$fn = function ($data) { return $data; };

