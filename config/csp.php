<?php

return [

    /*
     * A policy will determine which CSP headers will be set. A valid CSP policy is
     * any class that extends `Spatie\Csp\Policies\Policy`
     * 'policy' => Spatie\Csp\Policies\Basic::class,
     */
    'policy' =>  App\Csp\Policies\SecurePolicy::class,
    /*
    |--------------------------------------------------------------------------
    | CSP Policy Presets
    |--------------------------------------------------------------------------
    |
    | This section defines which Content Security Policy (CSP) presets should
    | be applied to each HTTP response. Presets are classes that extend
    | Spatie\Csp\Policies\Policy or AddCspHeaders and define specific
    | directives for script, style, image, and connection sources.
    |
    | Below, we register our custom middleware-based preset that injects
    | nonce-enabled script policies, allows  Google Analytics,
    | and sets additional security headers like Permissions-Policy.
    |
    */
    'presets' => [
        App\Csp\Policies\SecurePolicy::class,
    ],
    /*
     * This policy which will be put in report only mode. This is great for testing out
     * a new policy or changes to existing csp policy without breaking anything.
     */
    'report_only_policy' => '',

    /*
     * All violations against the policy will be reported to this url.
     * A great service you could use for this is https://report-uri.com/
     *
     * You can override this setting by calling `reportTo` on your policy.
     */
    'report_uri' => env('CSP_REPORT_URI', ''),

    /*
     * Headers will only be added if this setting is set to true.
     */
    'enabled' => env('CSP_ENABLED', true),

    /*
     * The class responsible for generating the nonces used in inline tags and headers.
     */
    'nonce_generator' => Spatie\Csp\Nonce\RandomString::class,

    /*
     * Set to false to disable automatic nonce generation and handling.
     * This is useful when you want to use 'unsafe-inline' for scripts/styles
     * and cannot add inline nonces. 
     * Note that this will make your CSP policy less secure.
     */
    'nonce_enabled' => env('CSP_NONCE_ENABLED', true),
];
