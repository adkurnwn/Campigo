<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Services\DatabaseBackupService;
use App\Services\StorageBackupService;  // Add this import
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class BackupPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationLabel = 'Backup';
    protected static ?string $title = 'Database Backup';
    protected static ?int $navigationSort = 90;
    protected static ?string $slug = 'backup';
    protected static string $view = 'filament.pages.backup';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download_backup')
                ->label('Database Backup')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    try {
                        $backupService = new DatabaseBackupService();
                        $filename = $backupService->generateBackup();
                        
                        Notification::make()
                            ->title('Backup created successfully')
                            ->body('Backup has been sent to your email')
                            ->success()
                            ->send();

                        return response()->download(
                            storage_path('app/backup/' . $filename)
                        )->deleteFileAfterSend();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Backup failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            
            Action::make('download_storage_backup')
                ->label('Storage Backup')
                ->icon('heroicon-o-folder-arrow-down')
                ->action(function () {
                    try {
                        $storageBackup = new StorageBackupService();
                        $filename = $storageBackup->generateBackup();
                        
                        Notification::make()
                            ->title('Storage backup created successfully')
                            ->success()
                            ->send();

                        return response()->download(
                            storage_path('app/backup/' . $filename)
                        )->deleteFileAfterSend();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Storage backup failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Action::make('restore_latest_backup')
                ->label('Restore Latest Backup')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->action(function () {
                    try {
                        $backupService = new DatabaseBackupService();
                        $latestBackup = $backupService->getLatestBackup();
                        
                        if (!$latestBackup) {
                            throw new \Exception('No backup file found');
                        }

                        $backupService->restoreFromBackup($latestBackup);
                        
                        Notification::make()
                            ->title('Database restored successfully')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Restore failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Action::make('restore_from_upload')
                ->label('Restore From File')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('warning')
                ->requiresConfirmation()
                ->form([
                    \Filament\Forms\Components\FileUpload::make('backup_file')
                        ->acceptedFileTypes(['application/sql', '.sql'])
                        ->required()
                ])
                ->action(function (array $data) {
                    try {
                        $backupService = new DatabaseBackupService();
                        $path = storage_path('app/public/' . $data['backup_file']);
                        
                        $backupService->restoreFromBackup($path);
                        
                        Notification::make()
                            ->title('Database restored successfully')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Restore failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
        ];
    }
}
