<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('module');//permet de savoir à quel module appartient le paramètre : VPN, SOC, core, ai, server
            $table->string('key');//permet de connaître le nom du paramètre : default_port, theme, timezone, hostname
            $table->text('value');//permet de connaître les valeurs comme : localhost, zone, ...
            $table->string('type')->default('string');//String, boolean, json, integer
            $table->timestamps();
            $table->unique(['module', 'key']);//Pour éviter qu'il y ai deux paramètres portant le même nom dans le même module
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
