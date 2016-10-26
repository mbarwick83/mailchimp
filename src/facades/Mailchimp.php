<?php 

namespace Mbarwick83\Mailchimp\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Mbarwick83\Mailchimp\Mailchimp
 */
class Mailchimp extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Mbarwick83\Mailchimp\Mailchimp';
    }
}