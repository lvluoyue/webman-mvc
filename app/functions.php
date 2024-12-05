<?php
/**
 * Here is your custom functions.
 */

if (!function_exists('docker_it')) {
    function docker_it(string $cmd, string $input, string &$output, string &$error, float &$runningTime = 0): void
    {
        $descriptorspec = [
            0 => ['pipe', 'r'], // 标准输入
            1 => ['pipe', 'w'], // 标准输出
            2 => ['pipe', 'w'], // 标准错误
        ];
        $startTime = microtime(true);
        $process = proc_open($cmd, $descriptorspec, $pipes);
        if (is_resource($process)) {
            if($input) {
                fwrite($pipes[0], $input);
            }
            fclose($pipes[0]);
            $output = mb_convert_encoding(stream_get_contents($pipes[1]), 'UTF-8', 'auto');
            $error = mb_convert_encoding(stream_get_contents($pipes[2]), 'UTF-8', 'auto');
            fclose($pipes[1]);
            fclose($pipes[2]);
        }
        $endTime = microtime(true);
        $runningTime = $endTime - $startTime;
    }
}