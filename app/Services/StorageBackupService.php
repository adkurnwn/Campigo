<?php

namespace App\Services;

use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Illuminate\Support\Facades\Log;

class StorageBackupService
{
    private ?ZipArchive $zip = null;

    public function generateBackup(): string
    {
        try {
            $backupDir = storage_path('app/backup');
            $filename = 'storage-backup-' . date('Y-m-d-H-i-s') . '.zip';
            $outputPath = $backupDir . DIRECTORY_SEPARATOR . $filename;

            // Create backup directory with proper Windows permissions
            if (!file_exists($backupDir)) {
                if (!mkdir($backupDir, 0777, true)) {
                    throw new \Exception('Unable to create backup directory');
                }
                // Windows-specific: Make directory writable for IIS/Apache
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    exec('icacls "' . $backupDir . '" /grant "Everyone":(OI)(CI)F /T');
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

            // Windows-specific: Make file readable/writable
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                exec('icacls "' . $outputPath . '" /grant "Everyone":F');
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
