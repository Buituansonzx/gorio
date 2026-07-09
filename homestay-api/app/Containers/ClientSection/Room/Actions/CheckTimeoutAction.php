<?php

namespace App\Containers\ClientSection\Room\Actions;

use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Support\Facades\DB;

class CheckTimeoutAction extends ParentAction
{
    public function run(array $data): array
    {
        $duration = $data['duration'] ?? 30; // Default 30 seconds
        $startTime = microtime(true);
        
        $systemInfo = $this->getSystemInfo();
        
        // Test different timeout scenarios
        $tests = [
            'database_connection' => $this->testDatabaseConnection(),
            'sleep_test' => $this->testSleep($duration),
            'memory_test' => $this->testMemoryUsage(),
            'execution_time_test' => $this->testExecutionTime(),
        ];
        
        $endTime = microtime(true);
        $totalExecutionTime = round(($endTime - $startTime) * 1000, 2); // milliseconds
        
        return [
            'system_info' => $systemInfo,
            'tests' => $tests,
            'total_execution_time_ms' => $totalExecutionTime,
            'requested_duration' => $duration,
            'timestamp' => now()->toISOString(),
        ];
    }
    
    private function getSystemInfo(): array
    {
        return [
            'php_version' => PHP_VERSION,
            'max_execution_time' => ini_get('max_execution_time'),
            'memory_limit' => ini_get('memory_limit'),
            'post_max_size' => ini_get('post_max_size'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'default_socket_timeout' => ini_get('default_socket_timeout'),
            'mysql_connect_timeout' => ini_get('mysql.connect_timeout'),
            'current_memory_usage' => memory_get_usage(true),
            'peak_memory_usage' => memory_get_peak_usage(true),
            'server_time' => now()->toISOString(),
            'timezone' => config('app.timezone'),
        ];
    }
    
    private function testDatabaseConnection(): array
    {
        $startTime = microtime(true);
        
        try {
            $result = DB::select('SELECT 1 as test, NOW() as current_time');
            $endTime = microtime(true);
            
            return [
                'status' => 'success',
                'execution_time_ms' => round(($endTime - $startTime) * 1000, 2),
                'result' => $result[0] ?? null,
            ];
        } catch (\Exception $e) {
            $endTime = microtime(true);
            
            return [
                'status' => 'error',
                'execution_time_ms' => round(($endTime - $startTime) * 1000, 2),
                'error' => $e->getMessage(),
            ];
        }
    }
    
    private function testSleep(int $duration): array
    {
        $startTime = microtime(true);
        
        try {
            sleep($duration);
            $endTime = microtime(true);
            
            return [
                'status' => 'success',
                'requested_duration' => $duration,
                'actual_duration_ms' => round(($endTime - $startTime) * 1000, 2),
                'difference_ms' => round((($endTime - $startTime) - $duration) * 1000, 2),
            ];
        } catch (\Exception $e) {
            $endTime = microtime(true);
            
            return [
                'status' => 'error',
                'execution_time_ms' => round(($endTime - $startTime) * 1000, 2),
                'error' => $e->getMessage(),
            ];
        }
    }
    
    private function testMemoryUsage(): array
    {
        $startMemory = memory_get_usage(true);
        
        // Allocate some memory
        $data = [];
        for ($i = 0; $i < 100000; $i++) {
            $data[] = str_repeat('x', 100);
        }
        
        $peakMemory = memory_get_usage(true);
        
        // Clean up
        unset($data);
        
        $endMemory = memory_get_usage(true);
        
        return [
            'status' => 'success',
            'start_memory_bytes' => $startMemory,
            'peak_memory_bytes' => $peakMemory,
            'end_memory_bytes' => $endMemory,
            'memory_allocated_bytes' => $peakMemory - $startMemory,
            'memory_freed_bytes' => $peakMemory - $endMemory,
            'start_memory_mb' => round($startMemory / 1024 / 1024, 2),
            'peak_memory_mb' => round($peakMemory / 1024 / 1024, 2),
            'end_memory_mb' => round($endMemory / 1024 / 1024, 2),
        ];
    }
    
    private function testExecutionTime(): array
    {
        $startTime = microtime(true);
        
        // CPU intensive task
        $result = 0;
        for ($i = 0; $i < 1000000; $i++) {
            $result += sqrt($i);
        }
        
        $endTime = microtime(true);
        
        return [
            'status' => 'success',
            'execution_time_ms' => round(($endTime - $startTime) * 1000, 2),
            'iterations' => 1000000,
            'result' => round($result, 2),
        ];
    }
}
