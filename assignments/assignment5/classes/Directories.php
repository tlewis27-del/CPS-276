<?php

class Directories {

    public function makeDirectory($folder, $content) {
        $base = __DIR__ . "/../directories/";
        $path = $base . $folder;

        // checks to see if the directory already exists
        if (file_exists($path)) {
            return [
                "ok" => false,
                "msg" => "A directory already exists with that name."
            ];
        }

        // creates directory if possible
        if (!mkdir($path, 0777, true)) {
            return [
                "ok" => false,
                "msg" => "Error: Could not create the directory."
            ];
        }

        // creates the readme file inside the directory
        $file = $path . "/readme.txt";
        $write = file_put_contents($file, $content);

        if ($write === false) {
            return [
                "ok" => false,
                "msg" => "Error: Could not create the file."
            ];
        }

        return [
            "ok" => true,
            "msg" => "File and directory were created."
        ];
    }
}
