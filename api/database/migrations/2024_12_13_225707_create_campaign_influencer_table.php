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
        Schema::create('campaign_influencer', function (Blueprint $table) {
            $table->foreignId('campaigns_id')->constrained('campaigns')->onDelete('cascade');
            $table->foreignId('influencers_id')->constrained('influencers')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

            $table->primary(['campaigns_id', 'influencers_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_influencer');
    }
};
