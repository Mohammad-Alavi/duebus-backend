<?php namespace Denora\Duebusverification\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDenoraDuebusverificationManagingPartnerIDs extends Migration
{
    public function up()
    {
        Schema::create('denora_duebusverification_managing_partner_ids', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('business_verification_id');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('denora_duebusverification_managing_partner_ids');
    }
}
