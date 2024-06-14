<?php declare(strict_types=1);


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('panes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->foreignId('user_id');
            $table->string('text');
            $table->string('font')->default('');
            $table->string('size');
            $table->string('colour');
            $table->integer('top');
            $table->integer('left')->default(0);
            $table->integer('width')->default(0);
            $table->integer('height')->default(0);
            $table->string('bgColour');
            $table->string('animationIn');
            $table->string('animationOut');
            $table->integer('showFor');
            $table->string('extraCss');
            $table->string('extraClasses');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('panes');
    }
};
