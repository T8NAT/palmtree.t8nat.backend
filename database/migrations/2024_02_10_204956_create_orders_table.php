<?php

use App\Models\Status;
use Database\Seeders\StatusSeeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        (new StatusSeeder)->run();
        $allowed_status = DB::table('statuses')->pluck('status_name')->toArray();
        Schema::create('orders', function (Blueprint $table) use($allowed_status){
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->unsignedBigInteger('selected_delivery_id')->nullable();
            $table->foreign('selected_delivery_id')->references('id')->on('users');
            $table->text('location_lat');
            $table->text('location_lng');
            $table->LongText('location_name');
            $table->text('destination_lat');
            $table->text('destination_lng');
            $table->LongText('destination_name');
            $table->string('seller_name');
            $table->text('customer_name');
            $table->text('insrtuctions')->nullable();
            $table->text('customer_notes')->nullable();
            $table->string('unique_id')->unique()->nullable();
            $table->string('attachment')->nullable();
            $table->enum('order_status',$allowed_status)->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
