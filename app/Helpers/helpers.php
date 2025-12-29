<?php

use Illuminate\Support\Facades\File;
use App\Models\Business;
use App\Models\BusinessTranslation;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Pelago\Emogrifier\CssInliner;
use Illuminate\Support\Facades\DB;

if (! function_exists('csp_nonce')) {
    /**
     * Retrieve the Content Security Policy (CSP) nonce for the current request.
     *
     * This nonce is generated per request in the HandleInertiaRequests middleware and is used
     * in inline <script> tags to comply with the CSP header.
     *
     * Usage in Blade or Inertia templates:
     * <script nonce="{{ csp_nonce() }}">
     *
     * @return string The base64-encoded CSP nonce
     */
    function csp_nonce(): string 
    {
        return app(\Spatie\Csp\Nonce::class)->getNonce();
    }
}

if (!function_exists('getTranslations')) {
    /**
     * Retrieves translation strings based on the business or a specific language section.
     *
     * This function determines the appropriate language code by:
     * - Prioritizing the business's associated language (if a Business instance is provided),
     * - Using the explicitly provided section as a fallback language code,
     * - Or falling back to the application's current locale.
     *
     * It then loads the corresponding JSON translation file and returns its contents as an array.
     *
     * @param string $language business language code
     * @param string|null $section Optional fallback language code if business is not provided.
     * @return array The decoded translation strings from the JSON language file.
     */
    function getTranslations(?string $language= null, ?string $section = null): array
    {
        if  (!$language) {
            $language = $section;
        }
                
        $langFile = lang_path("{$language}.json");
        $translations= File::exists($langFile) ? json_decode(File::get($langFile), true): [];
        return [
            'language' => $language,
            'translations' => $translations,
        ];
    }

    if (!function_exists('generateSlug')) {
        function generateSlug($string) {
            $map = [
                'š' => 's',
                'Š' => 's',
                'đ' => 'd',
                'Đ' => 'd',
                'ž' => 'z',
                'Ž' => 'z',
                'č' => 'c',
                'Č' => 'c',
                'ć' => 'c',
                'Ć' => 'c',
            ];
            $string = strtr($string, $map);
            $string = strtolower($string);
            $string = preg_replace('/[^a-z0-9]+/i', '-', $string);
            $string = trim($string, '-');
            return $string;
        }
    }
    if (!function_exists('getUserTimeZone')){
        function getUserTimeZone(){
            return'Europe/Sarajevo';
        }
    }
    /**
     * Convert local start date + time to UTC start and end datetime.
     *
     * @param string $startDate Date string in 'Y-m-d' format (local)
     * @param Carbon $timeSlot time of the appointment in local time 
     * @param int $totalDuration duration+resting_time in minutes (e.g. 55+5)
     *
     * @return array{
     *   time_slot: string,      // local time string HH:mm
     *   time_zone: string,      // user/business timezone
     *   starts_at_utc: Carbon,  // UTC datetime
     *   ends_at_utc: Carbon     // UTC datetime
     * }
     */
    if (!function_exists('getUserUtcDateTime')){
        function getUserUtcDateTime(string $startDate, Carbon $timeSlot, int $totalDuration = 60): array
        {
            $timeZone = getUserTimeZone();
            $hour = $timeSlot->format('H');
            $minute = $timeSlot->format('i');
            // Local datetime based on user/business timezone
            $localDateTime = Carbon::parse($startDate, $timeZone)
                ->setTime($hour, $minute, 0);

            // UTC start datetime
            $startsAtUtc = $localDateTime->copy()->setTimezone('UTC');
            $endsAtUtc = $startsAtUtc->copy()->addMinutes($totalDuration);

            return [
                'time_slot' => $localDateTime->format('H:i'),
                'time_zone' => $timeZone,
                'starts_at_utc' => $startsAtUtc,
                'ends_at_utc' => $endsAtUtc
            ];
        }
    }

    if (!function_exists('mapColumnsWithLabels'))
    {
        /**
         * Maps database result columns/fields to translated labels for display in tables.
         *
         * This function takes the first result from a query, extracts its attribute keys,
         * and maps them to their corresponding labels defined in the business-specific translation file,
         * falling back to ucfirst(field) if a translation is not found.
         *
         * @param \Illuminate\Support\Collection or array $result The query result collection (expects at least one record)
         * @param \App\Models\Business $business The business instance used to determine the language and translation file
         * @return array An array of field-label pairs, e.g. [['field' => 'service', 'label' => 'Usluga']]
         */
        function mapColumnsWithLabels(Collection|array $result, Business $business, bool $withId = false): array
        {
           if (is_array($result)) {
                $result = collect([$result]);
            }

            // Handle empty result safely
            $first = $result->first();
            $fields = [];

            if ($first instanceof \Illuminate\Database\Eloquent\Model) {
                $fields = array_keys($first->getAttributes());
            } elseif (is_array($first)) { 
                $fields = array_values($first);
            }

            $data = getTranslations($business->lang);
            $translations = $data['translations'];

            return collect($fields)
                ->reject(fn ($field) => !$withId && $field === 'id')
                ->map(fn ($field) => [
                    'field'    => $field,
                    'key'      => $field,
                    'label'    => $translations['tableColumns'][$field] ?? ucfirst(str_replace('_', ' ', $field)),
                    'sortable' => true
                ])
                ->values()
                ->toArray();
        }
    }
}
if (!function_exists('inlineEmailView')) {
   /**
     * Render a Blade email view and inline CSS styles.
     *
     * @param string $view   Blade view name (e.g. 'mail.business-created')
     * @param array  $data   Data passed to the view
     * @param string $cssPath Optional path to CSS file (default: public/css/email.css)
     *
     * @return string Inline-styled HTML
     */
    function inlineEmailView(string $view, array $data = [], string $cssPath = null): string
    {
        // Render the view into HTML
        $html = View::make($view, $data)->render();

        // Default CSS path
        $cssPath = $cssPath ?? resource_path('css/email.css');
        $exists = file_exists($cssPath);
     
        $css = $exists ? file_get_contents($cssPath) : '';
        $finalHtml = CssInliner::fromHtml($html)->inlineCss($css)->render();

        return $finalHtml;
    }
}
if (!function_exists('isValidEmail')) {
   /**
     * Check if email is valid
     *
     * @return bool trueor false
     */
    function isValidEmail(string $email = null): bool
    {
        return !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
if (!function_exists('isValidPhone')) {
   /**
     * Check if phone number is valid
     *
     * @return bool trueor false
     */
    function isValidPhone(string $number  = null): bool
    {
        if (!$number) return false;
        // Keep only digits and + at the start
        $normalized = preg_replace('/[^\d+]/', '', $number);
      
        // Must start with +
        if (str_starts_with($normalized, '+') === false) {
            return false;
        }
        $countries =DB::table('countries')->orderBy('short')->get();
     
        // Find matching country by phone code
        $country = null;
        foreach ($countries as $c) {
            $codeDigits = preg_replace('/\D/', '', $c->phone_code);
            if (str_starts_with($normalized, '+' . $codeDigits)) {
                $country = $c;
                break;
            }
        }

        if (!$country) {
            return false; // Unknown country code
        }
        
        // Remove country code
        $localNumber = substr($normalized, strlen(preg_replace('/\D/', '', $country->phone_code)) + 1); // +1 for +

        // Remove trunk prefix if the country uses it
        if (!empty($country->has_trunk_prefix) && str_starts_with($localNumber, '0')) {
            $localNumber = substr($localNumber, 1);
        }

        // Basic length check (7–15 digits)
        if (strlen($localNumber) < 7 || strlen($localNumber) > 15) {
            return false;
        }

        // Optional: check mobile prefix per country
        // For example, Bosnia: 60, 61, 62, 63, 65, 66, 67, 68
        $mobilePrefixes = [
            'BA' => ['60','61','62','63','65','66','67','68'],
            'DE' => ['15','16','17'],
            'GR' => ['69'],
            'NL' => ['6'],
            'FR' => ['6','7'],
        ];

        $prefixes = $mobilePrefixes[$country->short] ?? [];
        if (!empty($prefixes)) {
            $found = false;
            foreach ($prefixes as $prefix) {
                if (str_starts_with($localNumber, $prefix)) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                return false;
            }
        }

        return true;
    }
}


