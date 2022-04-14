<!DOCTYPE html>
<?php
session_start(); ?>

<link rel="stylesheet" type="text/css" href="styles.css">

<?php

use smartstats\player;

//use smartstats\team;
use smartstats\controller;

require "../smart-stats/classes/team.php";
require "../smart-stats/classes/player.php";
require_once "../smart-stats/database/api.php";
require "../smart-stats/classes/controller.php";

$id = "";
$homeTeam = "";
$awayTeam = "";

if (isset($_GET['homeTeam'])) {
    $homeTeam = $_GET['homeTeam'];
}

if (isset($_GET['awayTeam'])) {
    $awayTeam = $_GET['awayTeam'];
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

checkDatabaseForFixture($id, $homeTeam, $awayTeam);



// this function checks to see if the fixture data already exists in the database before calling the api; if the data is within the database, uses this data in the page
// instead of wasting an api call on the same data.

function checkDatabaseForFixture($id, $homeTeam, $awayTeam)
{
    $controller = new controller();
    $data = $controller->selectLineup($id);

    if ($data) {
        homeTeam($homeTeam, $data);
        awayTeam($awayTeam, $data);
    } else {
        generateFixture($id);
        header("Refresh:0");
    }


}

// generate feature is designed to check to see if the lineup exists in the api, if the lineup key is empty, the controller returns false and informs the user.
// if a lineup is found, the controller will then proceed to build the fixture data table, the team data table which relates to the fixture table
// it will then build the player data table which relates to the team table, which relates to the fixture table.

function homeTeam($homeTeam, $data)
{
    //echo "<div>$homeTeam</div>";
    echo "<table style='width: 300px; position: fixed; left: 0px; top: 0px'>";
    echo "<tr>";
    echo "<td>Position</td>";
    echo "<td>Player Name</td>";
    echo "</tr>";

    echo "<tr>";
    foreach ($data as $player) {

        if ($player['teamName'] == $homeTeam) {
            echo "<td>" . $player['position'] . "</td>";
            echo "<td>" . $player['name'] . "</td>";
        }
        echo "</tr>";
    }

    echo "</table>";
}

function awayTeam($awayTeam, $data)
{
    //echo "<div>$awayTeam</div>";
    echo "<table style='width: 300px; position: fixed; right: 0px; top: 0px'>";
    echo "<tr>";
    echo "<td>Position</td>";
    echo "<td>Player Name</td>";
    echo "</tr>";

    echo "<tr>";
    foreach ($data as $player) {

        if ($player['teamName'] == $awayTeam) {
            echo "<td>" . $player['position'] . "</td>";
            echo "<td>" . $player['name'] . "</td>";
        }
        echo "</tr>";
    }

    echo "</table>";
}

function generateFixture($id)
{
    $controller = new controller();

    $response = $controller->getLineup($id);

    if ($response === false) {
        echo "No lineup available";
    } else {

        $buildFixture = new controller();

        $buildFixture->buildFixture($id);

        foreach ($response['response'] as $team) {
            $teamID = $team['team']['id'];
            $teamName = $team['team']['name'];

            $controller->buildTeamData($teamID, $teamName, $id);

            echo "Inserting Player Data: ";

            foreach ($team['startXI'] as $player) {
                $playerID = $player['player']['id'];
                $playerName = $player['player']['name'];
                $playerPosition = $player['player']['pos'];
                $playerNumber = $player['player']['number'];

                $controller->buildPlayer($playerID, $playerName, $playerPosition, $playerNumber, $teamID);
            }
        }
    }
}

