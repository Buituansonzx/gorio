<?php

namespace App\Containers\SharedSection\Room\Tasks;

use App\Containers\AppSection\User\Models\User;
use App\Containers\SharedSection\Room\Models\Host;
use App\Ship\Helpers\PhoneHelper;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

final class ImportHostsTask extends ParentTask
{
    public function __construct()
    {
    }


    public function run($files)
    {
        foreach ($files as $file) {
            if (!$file->isValid()) {
                throw new \Exception('Invalid file upload.');
            }

            $rows = Excel::toArray([], $file)[0];
            unset($rows[0]);

                $usersData = [];
                $hostsData = [];

                foreach ($rows as $row) {
                    $email = trim($row[0] ?? '');
                    $firstName = trim($row[1] ?? '');
                    $lastName = trim($row[2] ?? '');
                    $phone = trim($row[3] ?? '');
                    $businessName = trim($row[4] ?? '');
                    $description = trim($row[5] ?? '');
                    $gender = trim($row[6] ?? '');
                    $birth = trim($row[7] ?? '');
                    $address = trim($row[8] ?? '');
                    $hotline = trim($row[9] ?? '');
                    $avatar = trim($row[10] ?? '');
                    $countryCode = trim($row[11] ?? '');
                    if (!$email) continue;

                    $phoneAnalysis = PhoneHelper::analyzePhone($phone, $countryCode);

                    $usersData[] = [
                        'email' => $email,
                        'name' => $firstName . ' ' . $lastName,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'country_code' => $phoneAnalysis['country_code'],
                        'phone' => $phoneAnalysis['formatted']['e164'],
                        'phone_number' => $phoneAnalysis['original'],
                        'phone_code' => $phoneAnalysis['international_code'],
                        'password' => bcrypt('123456'),
                        'gender' => $gender,
                        'birth' => is_numeric($birth)
                            ? Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($birth))->toDateString()
                            : ($birth ? Carbon::parse($birth)->toDateString() : null),
                        'email_verified_at' => now(),
                    ];

                    $hostsData[] = [
                        'user_email' => $email,
                        'business_name' => $businessName,
                        'avatar' => $avatar,
                        'description' => $description,
                        'address' => $address,
                        'hotline' => $hotline,
                    ];
                }

                // Tạo user
                $userIdMap = [];
                foreach ($usersData as $user) {
                    $createdUser = User::firstOrCreate(['email' => $user['email']], $user);
                    $userIdMap[$user['email']] = $createdUser->id;
                }

                // Tạo host
                foreach ($hostsData as $host) {
                    Host::create([
                        'business_name' => $host['business_name'],
                        'user_id' => $userIdMap[$host['user_email']],
                        'description' => $host['description'],
                        'avatar' => $host['avatar'],
                        'verified_status' => true,
                        'address' => $host['address'],
                        'hotline' => $host['hotline'],
                    ]);
                }

            return 'Import completed';
        }
    }
}
