<?php

namespace App\Services;

use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Illuminate\Support\Facades\Log;

class StorageBackupService
{
    private ?ZipArchive $zip = null;
    private const DEFAULT_DIR_PERMISSION = 0755;
    private const DEFAULT_FILE_PERMISSION = 0644;

    public function generateBackup(): string
    {
        try {
            $backupDir = storage_path('app/backup');
            $filename = 'storage-backup-' . date('Y-m-d-H-i-s') . '.zip';
            $outputPath = $backupDir . DIRECTORY_SEPARATOR . $filename;

            // Create backup directory with cross-platform permissions
            if (!file_exists($backupDir)) {
                if (!mkdir($backupDir, self::DEFAULT_DIR_PERMISSION, true)) {
                    throw new \Exception('Unable to create backup directory');
                }
            }

            // Initialize ZIP
            $this->zip = new ZipArchive();
            
            // Open ZIP file with explicit lock release
            if (file_exists($outputPath)) {
                @unlink($outputPath);
            }
            
            $zipResult = $this->zip->open($outputPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            if ($zipResult !== true) {
                throw new \Exception('Cannot create zip file. Error code: ' . $zipResult);
            }

            // Add files with better error handling
            $this->addFolderToZip(storage_path(), 'storage', ['backup', 'logs', 'framework', 'debugbar']);
            

            // Close ZIP file
            $this->zip->close();

            // Set proper file permissions for the zip file
            if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
                chmod($outputPath, self::DEFAULT_FILE_PERMISSION);
            }

            if (!file_exists($outputPath)) {
                throw new \Exception('ZIP file was not created');
            }

            return $filename;

        } catch (\Exception $e) {
            Log::error('Storage backup error: ' . $e->getMessage());
            
            if ($this->zip instanceof ZipArchive) {
                $this->zip->close();
            }
            
            if (isset($outputPath) && file_exists($outputPath)) {
                @unlink($outputPath);
            }
            
            throw new \Exception('Storage backup failed: ' . $e->getMessage());
        }
    }

    private function addFolderToZip(string $folder, string $rootFolder, array $excludeDirs = []): void
    {
        try {
            if (!is_readable($folder)) {
                throw new \Exception("Cannot read from {$rootFolder} folder");
            }

            $flags = RecursiveDirectoryIterator::SKIP_DOTS | RecursiveDirectoryIterator::FOLLOW_SYMLINKS;
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($folder, $flags),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($iterator as $file) {
                // Skip excluded directories and files
                $shouldExclude = false;
                foreach ($excludeDirs as $excludeDir) {
                    if (strpos($file->getPathname(), $folder . DIRECTORY_SEPARATOR . $excludeDir) !== false) {
                        $shouldExclude = true;
                        break;
                    }
                }
                
                if ($shouldExclude || !$file->isFile() || !$file->isReadable()) {
                    continue;
                }

                $filePath = $file->getRealPath();
                $relativePath = $rootFolder . '/' . substr($filePath, strlen($folder) + 1);

                if (!$this->zip->addFile($filePath, $relativePath)) {
                    Log::warning("Failed to add file to ZIP: $filePath");
                }
            }
        } catch (\Exception $e) {
            Log::error("Error processing {$rootFolder} folder: " . $e->getMessage());
            throw $e;
        }
    }
}
