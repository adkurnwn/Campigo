<?php

namespace App\Services;

use PDO;
use Illuminate\Support\Facades\Config;

class DatabaseBackupService
{
    public function generateBackup(): string
    {
        try {
            $database = Config::get('database.connections.mysql.database');
            $username = Config::get('database.connections.mysql.username');
            $password = Config::get('database.connections.mysql.password');
            $host = Config::get('database.connections.mysql.host');

            $filename = 'backup-' . date('Y-m-d-H-i-s') . '.sql';
            $outputPath = storage_path('app/backup/' . $filename);

            if (!file_exists(storage_path('app/backup'))) {
                mkdir(storage_path('app/backup'), 0755, true);
            }

            $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
            
            $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
            
            $output = "-- Database backup created at " . date('Y-m-d H:i:s') . "\n\n";
            $output .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tables as $table) {
                // Get create table syntax
                $stmt = $pdo->query("SHOW CREATE TABLE `$table`");
                $row = $stmt->fetch(PDO::FETCH_NUM);
                $output .= "\n\n" . $row[1] . ";\n\n";

                // Get table data
                $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_NUM);
                if (!empty($rows)) {
                    $output .= "INSERT INTO `$table` VALUES\n";
                    $rowsOutput = [];
                    foreach ($rows as $row) {
                        $values = array_map(function ($value) use ($pdo) {
                            if ($value === null) {
                                return 'NULL';
                            }
                            return $pdo->quote($value);
                        }, $row);
                        $rowsOutput[] = "(" . implode(',', $values) . ")";
                    }
                    $output .= implode(",\n", $rowsOutput) . ";\n";
                }
            }

            $output .= "\nSET FOREIGN_KEY_CHECKS=1;";
            
            file_put_contents($outputPath, $output);

            return $filename;
        } catch (\Exception $e) {
            \Log::error('Database backup error: ' . $e->getMessage());
            throw new \Exception('Database backup failed: ' . $e->getMessage());
        }
    }
}
