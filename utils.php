<?php

    namespace Zerokelvin;
    use DateTime;

    /**
     * Un insieme di funzioni creato con lo scopo di velocizzare lo sviluppo
     */
    trait Utils{
        /**
         * Scrive un messaggio nel terminale
         * @param string $message il messaggio
         * @param bool $new_line deve andare a capo?
         * @return void
         */
        public static function message_out(string $message, bool $new_line = true): void{
            echo $message . ($new_line ? "\n" : null);
        }

        /**
         * Crea una riga vuota nel terminale
         * @return void
         */
        public static function new_line(): void{
            echo "\n";
        }

        /**
         * Chiede dei dati all'utente
         * @param string $prompt il messaggio, il prompt
         * @param string $expected_type il tipo di dato che il programma si aspetta di ricevere
         * @return string|int|float
         */
        public static function ask_data(string $prompt, string $expected_type = "string"): string|int|float{
            $data = readline("$prompt: ");

            return match($expected_type){
                "int" => (int) $data,
                "float" => (float) $data,
                default => $data
            };
        }

        /**
         * Formatta i dati di un allenamento in una tabella
         * @param string $json_path il percorso del file .json
         * @return void
         */
        public static function format_data(string $json_path){
            try{
                $data = json_decode(file_get_contents($json_path), true);
            }catch(\Throwable $e){
                Utils::message_out("Avviso: file $json_path non trovato.");
                exit();
            }

            $date = new DateTime;
            $date->setTimestamp($data["timestamp"]);

            $output = $data["record_name"]."\t\t".$date->format("D, d M Y H:i:s\n\n\n");
            $output .= "stile\t\ttempo\tpasso\n\n";

            foreach($data["results"] as $style_name => $style_data){
                $output .= "$style_name\n";

                foreach($style_data as $distance => $time){
                    $pace = number_format($time / (int)$distance * 100, 0);
                    $pace_output = $pace;

                    $x = (int)($pace / 60);
                    $pace_output = "$x'".($pace - $x * 60)."\"";

                    $output .= "  {$distance}m\t\t{$time}s\t$pace_output/100m\n";
                }

                $output .= "\n";
            }

            Utils::new_line();
            echo $output;
        }
    }