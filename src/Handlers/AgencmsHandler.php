<?php

namespace Agencms\Core\Handlers;

use Agencms\Core\Field;
use Agencms\Core\Group;
use Agencms\Core\Route;
use Agencms\Core\Option;
use Agencms\Core\Relationship;
use Agencms\Core\Facades\Agencms;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Agencms\Core\Middleware\AgencmsConfig;
use Illuminate\Support\Facades\Route as Router;

class AgencmsHandler
{
    /**
     * Register router middleware as plugin for Agencms.
     *
     * @return void
     */
    public static function register()
    {
        Router::aliasMiddleware('agencms.core', AgencmsConfig::class);
        Agencms::registerPlugin('agencms.core');
    }

    /**
     * Register all routes and models for the Admin GUI (AUI)
     *
     * @return void
     */
    public static function registerAdmin()
    {
        if (!Gate::allows('admin_access')) {
            return;
        }

        self::registerSettings();
    }

    /**
     * Append the general settings with additional settings for the Pages plugin
     *
     * @return void
     */
    public static function registerSettings()
    {
        if (!Gate::allows('settings_read')) {
            return;
        }

        Agencms::appendRoute(
            Route::load('settings')
                ->addGroup(
                    Group::full('Site')->key('site')->addField(
                        Field::string('title', 'Website Title'),
                        Field::string('title_prefix', 'Title Prefix'),
                        Field::string('title_suffix', 'Title Suffix')
                    )
                )
                ->addGroup(
                    Group::full('Analytics')->key('analytics')->addField(
                        Field::string('ga_code', 'Google Analytics Id')
                    )
                )
        );
    }
}
