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
        Schema::create('assets', function (Blueprint $table) {
            $table->id('id');
            $table->string('assetId');
            $table->string('Department');
            $table->string('Section');
            $table->string('assetName');
            $table->string('financialAssetId');
            $table->string('vendorName');
            $table->string('number');
            $table->string('email')->unique();
            $table->string('vendorAddress');
            $table->string('assetType');
            $table->string('manufaturer');
            $table->string('assetModel');
            $table->string('poNo');
            $table->string('invoiceNo');
            $table->string('typeWarranty');
            $table->string('warrantyStartDate');
            $table->string('warrantyEndDate');
            $table->string('warrantyDocument');
            $table->string('uploadDocument');
            $table->string('description');
            $table->string('assetImage');
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
        Schema::dropIfExists('asset');
    }
};
