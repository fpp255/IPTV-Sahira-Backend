<?php

namespace App\Controllers;

use App\Models\GuestModel;
use Config\Database;
use ZipArchive;

class Cron extends BaseController
{
    /* =========================
     * AUTO CHECKOUT (CRON)
     * ========================= */
    public function autoCheckout()
    {
        if (php_sapi_name() !== 'cli') {
            exit('Forbidden');
        }

        echo "AUTO CHECKOUT STARTED\n";

        $guestModel = new GuestModel();
        $today = date('Y-m-d');

        $guests = $guestModel
            ->where('checkout_date <=', $today)
            ->whereIn('status', ['ACTIVE'])
            ->where('deleted_at', null)
            ->findAll();

        if (empty($guests)) {
            echo "No guests to checkout\n";
            return;
        }

        foreach ($guests as $guest) {
            $guestModel->update($guest['id'], [
                'status'     => 'CHECKOUT',
                'device_id'  => null,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            echo "Checked out room {$guest['room_no']} (ID {$guest['id']})\n";
        }

        echo "AUTO CHECKOUT COMPLETED\n";
    }

    /* =========================
     * BACKUP DATABASE (CRON)
     * ========================= */
    public function backupDatabase()
    {
        if (php_sapi_name() !== 'cli') {
            exit('Forbidden');
        }

        echo "DATABASE BACKUP STARTED\n";

        // hapus backup lama
        $this->deleteOldBackups(10);

        $db = Database::connect();

        $host   = $db->hostname;
        $user   = $db->username;
        $pass   = $db->password;
        $dbname = $db->database;

        $backupDir = WRITEPATH . 'backups/database/';
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $timestamp = date('Ymd_His');
        $sqlFile   = $backupDir . "{$dbname}_{$timestamp}.sql";
        $zipFile   = $backupDir . "{$dbname}_{$timestamp}.zip";

        // dump DB
        $command = sprintf(
            'mysqldump -h%s -u%s -p%s %s > %s',
            escapeshellarg($host),
            escapeshellarg($user),
            escapeshellarg($pass),
            escapeshellarg($dbname),
            escapeshellarg($sqlFile)
        );

        exec($command, $output, $result);

        if ($result !== 0 || !file_exists($sqlFile)) {
            echo "Database dump failed\n";
            return;
        }

        // zip
        $zip = new ZipArchive();
        if ($zip->open($zipFile, ZipArchive::CREATE) === TRUE) {
            $zip->addFile($sqlFile, basename($sqlFile));
            $zip->close();
            unlink($sqlFile);

            echo "Backup created: {$zipFile}\n";

            // kirim email
            $this->sendBackupEmail($zipFile);
        } else {
            echo "Failed to zip backup\n";
        }

        echo "DATABASE BACKUP COMPLETED\n";
    }

    /* =========================
     * DELETE OLD BACKUP
     * ========================= */
    protected function deleteOldBackups($days = 10)
    {
        $backupDir = WRITEPATH . 'backups/database/';
        if (!is_dir($backupDir)) return;

        $expired = time() - ($days * 86400);

        foreach (glob($backupDir . '*.zip') as $file) {
            if (filemtime($file) < $expired) {
                unlink($file);
                echo "Deleted old backup: " . basename($file) . "\n";
            }
        }
    }

    /* =========================
     * SEND EMAIL
     * ========================= */
    protected function sendBackupEmail($zipPath)
    {
        if (!file_exists($zipPath)) return;

        $email = \Config\Services::email();

        $email->setFrom('no-reply@salamdjourney.com', 'no-reply@salamdjourney.com');
        $email->setTo('yerblues6@gmail.com');
        $email->setCC('febriansyah@gmail.com');

        $email->setSubject('HMS Database Backup - ' . date('d M Y'));
        $email->setMessage("
            <p>This is an automatic database backup.</p>
            <p><b>Date:</b> ".date('d-m-Y H:i')."</p>
        ");

        $email->attach($zipPath);

        if ($email->send()) {
            echo "Backup email sent\n";
        } else {
            echo "Email failed\n";
        }
    }
}
