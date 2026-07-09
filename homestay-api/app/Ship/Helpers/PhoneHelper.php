<?php

namespace App\Ship\Helpers;

use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberType;

class PhoneHelper
{
    /**
     * Chuẩn hóa và nhận diện thông tin số điện thoại
     * 
     * @param string $phoneNumber Số điện thoại đầu vào
     * @param string|null $defaultCountry Mã quốc gia mặc định (VN nếu không có)
     * @return array Thông tin chi tiết về số điện thoại
     */
    public static function analyzePhone(string $phoneNumber, ?string $defaultCountry = 'VN'): array
    {
        $phoneNumber = trim($phoneNumber);
        $phoneUtil = PhoneNumberUtil::getInstance();
        
        try {
            // Parse số điện thoại
            $parsedNumber = $phoneUtil->parse($phoneNumber, $defaultCountry);
            
            // Kiểm tra tính hợp lệ
            if (!$phoneUtil->isValidNumber($parsedNumber)) {
                throw new \Exception('Invalid phone number');
            }
            
            $countryCode = $phoneUtil->getRegionCodeForNumber($parsedNumber);
            $numberType = $phoneUtil->getNumberType($parsedNumber);
            
            return [
                'success' => true,
                'original' => $phoneNumber,
                'country_code' => $countryCode,
                'country_name' => self::getCountryName($countryCode),
                'international_code' => $parsedNumber->getCountryCode(),
                'formatted' => [
                    'e164' => $phoneUtil->format($parsedNumber, PhoneNumberFormat::E164),
                    'international' => $phoneUtil->format($parsedNumber, PhoneNumberFormat::INTERNATIONAL),
                    'national' => $phoneUtil->format($parsedNumber, PhoneNumberFormat::NATIONAL),
                    'rfc3966' => $phoneUtil->format($parsedNumber, PhoneNumberFormat::RFC3966),
                ],
                'validation' => [
                    'is_valid' => true,
                    'is_mobile' => $numberType === PhoneNumberType::MOBILE || $numberType === PhoneNumberType::FIXED_LINE_OR_MOBILE,
                    'type' => self::getPhoneNumberTypeName((int)$numberType),
                    'possible_types' => [self::getPhoneNumberTypeName((int)$numberType)],
                ],
                'carrier' => [
                    'name' => null, // libphonenumber doesn't provide carrier info
                ],
            ];
        } catch (NumberParseException | \Exception $e) {
            // Nếu không parse được, thử một số pattern phổ biến
            return self::fallbackAnalysis($phoneNumber, $e->getMessage());
        }
    }

    /**
     * Phân tích fallback khi thư viện không nhận diện được
     */
    private static function fallbackAnalysis(string $phoneNumber, string $error): array
    {
        $patterns = [
            'VN' => [
                'pattern' => '/^(\+84|84|0)([3-9]\d{8})$/',
                'name' => 'Vietnam',
                'code' => 84,
            ],
            'US' => [
                'pattern' => '/^(\+1|1)?([2-9]\d{2}[2-9]\d{2}\d{4})$/',
                'name' => 'United States',
                'code' => 1,
            ],
            'CN' => [
                'pattern' => '/^(\+86|86)?(1[3-9]\d{9})$/',
                'name' => 'China',
                'code' => 86,
            ],
        ];

        foreach ($patterns as $country => $info) {
            if (preg_match($info['pattern'], $phoneNumber, $matches)) {
                $localNumber = end($matches);
                return [
                    'success' => false,
                    'fallback' => true,
                    'original' => $phoneNumber,
                    'country_code' => $country,
                    'country_name' => $info['name'],
                    'international_code' => $info['code'],
                    'formatted' => [
                        'e164' => '+' . $info['code'] . $localNumber,
                        'international' => '+' . $info['code'] . ' ' . $localNumber,
                        'national' => $localNumber,
                    ],
                    'validation' => [
                        'is_valid' => true,
                        'is_mobile' => true,
                        'type' => 'mobile',
                    ],
                    'error' => $error,
                ];
            }
        }

        return [
            'success' => false,
            'original' => $phoneNumber,
            'error' => $error,
            'suggestion' => 'Vui lòng nhập số điện thoại theo định dạng: +[mã quốc gia][số điện thoại] hoặc 0xxxxxxxxx cho số Việt Nam',
        ];
    }

    /**
     * Validate số điện thoại với country cụ thể
     */
    public static function validatePhone(string $phoneNumber, string $country = 'VN'): bool
    {
        try {
            $phoneUtil = PhoneNumberUtil::getInstance();
            $parsedNumber = $phoneUtil->parse($phoneNumber, $country);
            return $phoneUtil->isValidNumber($parsedNumber);
        } catch (NumberParseException | \Exception $e) {
            return false;
        }
    }

    /**
     * Format số điện thoại về E.164 (chuẩn quốc tế)
     */
    public static function formatToE164(string $phoneNumber, string $defaultCountry = 'VN'): ?string
    {
        try {
            $phoneUtil = PhoneNumberUtil::getInstance();
            $parsedNumber = $phoneUtil->parse($phoneNumber, $defaultCountry);
            
            if ($phoneUtil->isValidNumber($parsedNumber)) {
                return $phoneUtil->format($parsedNumber, PhoneNumberFormat::E164);
            }
            return null;
        } catch (NumberParseException | \Exception $e) {
            return null;
        }
    }

    /**
     * Lấy tên quốc gia từ country code
     */
    private static function getCountryName(string $countryCode): string
    {
        $countries = [
            'VN' => 'Vietnam',
            'US' => 'United States',
            'CN' => 'China',
            'JP' => 'Japan',
            'KR' => 'South Korea',
            'TH' => 'Thailand',
            'SG' => 'Singapore',
            'MY' => 'Malaysia',
            'ID' => 'Indonesia',
            'PH' => 'Philippines',
        ];
        
        return $countries[$countryCode] ?? $countryCode;
    }

    /**
     * Chuyển đổi phone number type thành tên
     */
    private static function getPhoneNumberTypeName(int $type): string
    {
        $types = [
            PhoneNumberType::FIXED_LINE => 'fixed_line',
            PhoneNumberType::MOBILE => 'mobile',
            PhoneNumberType::FIXED_LINE_OR_MOBILE => 'mobile',
            PhoneNumberType::TOLL_FREE => 'toll_free',
            PhoneNumberType::PREMIUM_RATE => 'premium_rate',
            PhoneNumberType::SHARED_COST => 'shared_cost',
            PhoneNumberType::VOIP => 'voip',
            PhoneNumberType::PERSONAL_NUMBER => 'personal',
            PhoneNumberType::PAGER => 'pager',
            PhoneNumberType::UAN => 'uan',
            PhoneNumberType::VOICEMAIL => 'voicemail',
            PhoneNumberType::UNKNOWN => 'unknown',
        ];
        
        return $types[$type] ?? 'unknown';
    }

    /**
     * Lấy danh sách các country code được hỗ trợ phổ biến
     */
    public static function getSupportedCountries(): array
    {
        return [
            'VN' => ['name' => 'Vietnam', 'code' => 84, 'format' => '0xxxxxxxxx'],
            'US' => ['name' => 'United States', 'code' => 1, 'format' => '+1xxxxxxxxxx'],
            'CN' => ['name' => 'China', 'code' => 86, 'format' => '+86xxxxxxxxxxx'],
            'JP' => ['name' => 'Japan', 'code' => 81, 'format' => '+81xxxxxxxxxx'],
            'KR' => ['name' => 'South Korea', 'code' => 82, 'format' => '+82xxxxxxxxx'],
            'TH' => ['name' => 'Thailand', 'code' => 66, 'format' => '+66xxxxxxxxx'],
            'SG' => ['name' => 'Singapore', 'code' => 65, 'format' => '+65xxxxxxxx'],
            'MY' => ['name' => 'Malaysia', 'code' => 60, 'format' => '+60xxxxxxxxx'],
            'ID' => ['name' => 'Indonesia', 'code' => 62, 'format' => '+62xxxxxxxxxx'],
            'PH' => ['name' => 'Philippines', 'code' => 63, 'format' => '+63xxxxxxxxxx'],
        ];
    }
}
