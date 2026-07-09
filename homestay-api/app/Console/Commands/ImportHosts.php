<?php

namespace App\Console\Commands;

use App\Containers\AppSection\User\Models\User;
use App\Containers\SharedSection\Room\Models\Host;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportHosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-hosts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info("app:import-hosts started...");

        $filePath = storage_path('app/public/host-test.xlsx');

        if (!file_exists($filePath)) {
            $this->error("File not found at: $filePath");
            return 1;
        }

        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray(null, true, true, true);

        $header = array_shift($rows); // bỏ dòng tiêu đề

        foreach ($rows as $row) {
            try {
                $phone = $row['A'];
                $name = $row['B'];
                $email = $row['C'];
                $businessName = $row['D'];
                $description = $row['E'];
                $gender = $row['F'];
                $birth = $row['G'];

                // Tìm user theo số điện thoại
                $user = User::where('phone', $phone)->first();

                // Nếu chưa có user thì tạo mới
                if (!$user) {
                    $user = User::create([
                        'name'      => $name,
                        'email'     => $email,
                        'phone'     => $phone,
                        'gender'    => $gender,
                        'birth'     => $birth,
                        'email_verified_at' => now(),
                        'password'  => bcrypt('12345678'),
                    ]);

                    $this->info("Created new user: $phone");
                }

                // Tạo host gắn với user
                Host::create([
                    'user_id'       => $user->id,
                    'business_name' => $businessName,
                    'description'   => $description,
                    'verified_status' => true,
                ]);
            } catch (\Exception $e) {
                $this->error("Error importing row: " . json_encode($row));
                $this->error($e->getMessage());
            }
        }

        $this->info("Import completed.");
        return 0;
    }
}
