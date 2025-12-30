<?php
namespace App\Csp\Policies;

use Spatie\Csp\Policies\Policy;
use Illuminate\Support\Facades\Log;
use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;

class SecurePolicy extends Policy
{
    public function configure(): void
    { 
        if (app()->environment('production')) { 

            $this->addDirective(Directive::DEFAULT, Keyword::SELF);
            $this->addNonceForDirective(Directive::SCRIPT);
            $this->addNonceForDirective(Directive::STYLE);
            $this->addDirective(Directive::SCRIPT, [
                Keyword::SELF,
                'https://*.google-analytics.com',
            ]);
            
         
            $this->addDirective('script-src-elem', [
                Keyword::SELF,
                'https://*.google-analytics.com',
            ]);
            $this->addNonceForDirective('script-src-elem');

            $this->addDirective(Directive::STYLE, Keyword::SELF); 

            $this->addDirective('style-src-elem', [
                Keyword::SELF,
                'https://docdms.com',
            ]);
            $this->addNonceForDirective('style-src-elem');

          
            $this->addDirective(Directive::IMG, [
                Keyword::SELF,
                'data:',
                'blob:',
            ]);
        } 
    }
}
