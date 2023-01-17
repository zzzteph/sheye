<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_part_domains', function (Blueprint $table) {
            $table->id();
			
						$table->foreignId('domain_id')
      ->constrained()
      ->onUpdate('cascade')
      ->onDelete('cascade');
			
			$table->foreignId('sub_part_id')
      ->constrained()
      ->onUpdate('cascade')
      ->onDelete('cascade');
			
			
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_part_domains');
    }
};
