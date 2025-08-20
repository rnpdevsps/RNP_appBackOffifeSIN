<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAvatarToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // if not exist, add the new column
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar',191)->default(config('chatify.user_avatar.default'))->after('remember_token');
            }

            
            /*if (!Schema::hasColumn('users', 'address')) {
                $table->string('address',191)->nullable();
            }
            if (!Schema::hasColumn('users', 'country')) {
              $table->string('country',191)->default('Honduras');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone',191)->nullable();
            }*/

            //$table->string('country_code',191)->after('country')->nullable()->after('active_status');
            //$table->timestamp('phone_verified_at')->after('phone')->nullable()->after('country_code');


            /*if (!Schema::hasColumn('users', 'dark_layout')) {
                $table->boolean('dark_layout')->default(0)->after('phone_verified_at');
            }
            if (!Schema::hasColumn('users', 'rtl_layout')) {
                $table->boolean('rtl_layout')->default(0)->after('dark_layout');
            }
            if (!Schema::hasColumn('users', 'transprent_layout')) {
                $table->boolean('transprent_layout')->default(1)->after('rtl_layout');
            }
            if (!Schema::hasColumn('users', 'theme_color')) {
                $table->string('theme_color',191)->default('theme-2')->after('transprent_layout');
            }
            if (!Schema::hasColumn('users', 'users_grid_view')) {
                $table->boolean('users_grid_view')->default(0)->after('theme_color');
            }
            if (!Schema::hasColumn('users', 'forms_grid_view')) {
                $table->boolean('forms_grid_view')->default(0)->after('users_grid_view');
            }*/

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
