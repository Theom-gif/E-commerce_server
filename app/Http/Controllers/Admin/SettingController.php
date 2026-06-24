<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Cache::rememberForever('admin_settings', function () {
            return [
                'store_name' => (string) config('app.name'),
                'currency' => 'USD',
                'timezone' => (string) config('app.timezone'),
            ];
        });

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'currency' => ['required', 'string', 'size:3'],
            'timezone' => ['required', 'string', 'max:100'],
        ]);

        Cache::forever('admin_settings', $data);
        config(['app.name' => $data['store_name']]);

        return back()->with('status', __('Settings updated successfully.'));
    }

    public function testMail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        try {
            if (function_exists('mail')) {
                $headers = "From: " . (string) config('app.name') . " <" . (string) config('mail.from.address') . ">\r\nContent-Type: text/plain\r\n";
                mail($request->input('email'), 'Test Email from Admin Panel', "This is a test email from your admin panel.\nOS:".php_uname());
            }

            return back()->with('status', __('Test email sent successfully.'));
        } catch (\Throwable $e) {
            return back()->withErrors(['mail' => 'Mail error: ' . $e->getMessage()]);
        }
    }

    public function testSms(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'min:7'],
        ]);

        \Cache::forever('admin_latest_sms_test', [
            'to' => $request->input('phone'),
            'result' => 'Test SMS simulated successfully.',
            'tested_at' => now()->toDateTimeString(),
        ]);

        return back()->with('status', __('Simulated SMS test recorded successfully.'));
    }

    public function exportDatabase()
    {
        try {
            $dbName = (string) env('DB_DATABASE');
            $dbUser = (string) env('DB_USERNAME');
            $dbPass = (string) env('DB_PASSWORD');
            $dbHost = (string) env('DB_HOST', '127.0.0.1');
            $dbPort = (string) env('DB_PORT', '3306');
            $backupDir = storage_path('app/backups');

            if (!is_dir($backupDir) && !mkdir($backupDir, 0777, true) && !is_dir($backupDir)) {
                return back()->withErrors(['export' => 'Backup directory not writable.']);
            }

            $sqlFile = escapeshellarg($backupDir . DIRECTORY_SEPARATOR . date('Y-m-d_H-i-s') . '_ecommerce.sql');
            $command = "mysqldump -h " . escapeshellarg($dbHost) . " -P " . escapeshellarg($dbPort) . " -u " . escapeshellarg($dbUser) . ($dbPass === '' ? ' ' : " -p" . escapeshellarg($dbPass) . " ") . escapeshellarg($dbName) . " > {$sqlFile} 2>&1";

            passthru($command, $exitCode);

            if ($exitCode !== 0 || !is_file(trim($sqlFile, "'"))) {
                return back()->withErrors(['export' => 'Database export failed. Exit code ' . $exitCode]);
            }

            $filePath = trim($sqlFile, "'");

            return response()->download($filePath, null, ['Content-Type' => 'application/sql'])->deleteFileAfterSend(true);
        } catch (\Throwable $e) {
            return back()->withErrors(['export' => 'Database export error: '.$e->getMessage()]);
        }
    }
}
