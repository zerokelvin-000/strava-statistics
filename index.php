<?php
    require_once "utils.php";
    require_once "controllers/nuoto.php";

    use Zerokelvin\Utils;
    use Zerokelvin\Nuoto;
    
    Utils::new_line();
    Utils::message_out("Tool per l'analisi di attività Strava");
    Utils::message_out("Zerokelvin <zerokelvin.business@gmail.com>");
    Utils::message_out("Copyright: SAMT Lugano, sezione informatica");
    Utils::new_line();

    while(true){
        $sport_temp = Utils::ask_data("Inserisci lo sport dell'allenamento");

        $sport = match($sport_temp){
            "nuoto" => $sport_temp,
            "bici" => $sport_temp,
            "corsa" => $sport_temp,
            default => "invalid"
        };

        if($sport != "invalid"){
            break;
        }

        Utils::message_out("Inserisci uno sport valido.");
    }

    $laps = Utils::ask_data("Quanti giri ha il tuo allenamento?", "int");

    switch($sport){
        case "nuoto":
            Nuoto::main();
            break;
        default:
            Utils::message_out("Ops... errore!");
            break;
    }