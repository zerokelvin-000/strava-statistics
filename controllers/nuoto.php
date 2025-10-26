<?php

    namespace Zerokelvin;

    trait Nuoto{
        public static function main(){
            global $laps;

            $bests = [
                "delfino" => [
                    "25" => INF,
                    "50" => INF,
                    "100" => INF,
                    "150" => INF,
                    "200" => INF,
                    "300" => INF,
                    "400" => INF,
                    "500" => INF,
                ],
                "dorso" => [
                    "25" => INF,
                    "50" => INF,
                    "100" => INF,
                    "150" => INF,
                    "200" => INF,
                    "300" => INF,
                    "400" => INF,
                    "500" => INF,
                ],
                "rana" => [
                    "25" => INF,
                    "50" => INF,
                    "100" => INF,
                    "150" => INF,
                    "200" => INF,
                    "300" => INF,
                    "400" => INF,
                    "500" => INF,
                ],
                "stile" => [
                    "25" => INF,
                    "50" => INF,
                    "100" => INF,
                    "150" => INF,
                    "200" => INF,
                    "300" => INF,
                    "400" => INF,
                    "500" => INF,
                ],
            ];

            $values_buffer = [
                "style" => null,
                "distance" => null,
            ];

            $record_name = "Allenamento di nuoto";
            $name = Utils::ask_data("Inserisci il nome dell'allenamento ($record_name)");
            
            if($name){
                $record_name = $name;
            }

            for($lap = 0; $lap < $laps; $lap++){
                Utils::new_line();
                Utils::message_out("Giro n.".$lap + 1);

                while(true){
                    if(!$values_buffer["style"]){
                        $style = Utils::ask_data("Che stile hai usato?");

                        if(key_exists($style, $bests)){
                            $values_buffer["style"] = $style;
                            break;
                        }
                    }else{
                        $style = Utils::ask_data("Che stile hai usato (".$values_buffer["style"].")?");

                        if(!$style){
                            $style = $values_buffer["style"];
                            break;
                        }

                        if(key_exists($style, $bests)){
                            $values_buffer["style"] = $style;
                            break;
                        }
                    }

                    Utils::message_out("Inserisci uno stile valido.");
                }

                while(true){
                    if(!$values_buffer["distance"]){
                        $distance = Utils::ask_data("Che distanza hai coperto?");

                        if(key_exists($distance, $bests[$style])){
                            $values_buffer["distance"] = $distance;
                            break;
                        }
                    }else{
                        $distance = Utils::ask_data("Che distanza hai coperto (".$values_buffer["distance"].")?");

                        if(!$distance){
                            $distance = $values_buffer["distance"];
                            break;
                        }

                        if(key_exists($distance, $bests[$style])){
                            $values_buffer["distance"] = $distance;
                            break;
                        }
                    }

                    Utils::message_out("Inserisci una distanza valida.");
                }

                while(true){
                    $time = Utils::ask_data("Quanto tempo ci hai impiegato in secondi?", "float");

                    if($time > 0){
                        break;
                    }

                    Utils::message_out("Inserisci un tempo valido.");
                }

                $v_temp = $bests[$style][$distance];
                $v = min($v_temp, $time);

                if($v_temp == $v){
                    Utils::message_out("Il tempo per {$style} a {$distance}m è rimasto di {$v}s.");
                }else{
                    $bests[$style][$distance] = $v;

                    Utils::message_out("Il tempo per {$style} a {$distance}m è cambiato a {$v}s.");
                }
            }

            Utils::new_line();
            Utils::message_out("Sto salvando i risultati...");

            foreach($bests as $style_key => $style_best){
                foreach($style_best as $distance_key => $time){
                    if($time != INF){
                        continue;
                    }

                    unset($bests[$style_key][$distance_key]);
                }
            }

            $time = time(); // salvo in una variabile per essere sicuro che ci sia coerenza

            $filename = __DIR__."/../results/results_".$time.".json";

            $output = [
                "record_name" => $record_name,
                "results" => $bests,
                "timestamp" => $time,
            ];

            file_put_contents($filename, json_encode($output));

            Utils::message_out("Risultati salvati in ".basename($filename, "results/")." con successo.");

            $format_data = Utils::ask_data("Vuoi formattare i dati elaborati e creare una tabella (Y/n)");
            
            if($format_data == "n"){
                Utils::message_out("Grazie per aver usato questo tool, alla prossima!");
                exit();
            }

            Utils::format_data($filename);
            Utils::message_out("Grazie per aver usato questo tool, alla prossima!");
            exit();
        }
    }