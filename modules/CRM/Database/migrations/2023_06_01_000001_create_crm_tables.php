<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrmTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create contacts table
        Schema::create('crm_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('status')->default('Lead'); // Lead, Customer, Prospect, Partner
            $table->integer('lead_score')->default(0);
            $table->date('last_contact')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->json('tags')->nullable();
            $table->text('notes')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->string('website')->nullable();
            $table->string('source')->nullable(); // Where the contact came from
            $table->timestamps();
            $table->softDeletes();
        });

        // Create leads table
        Schema::create('crm_leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained('crm_contacts')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('New'); // New, Contacted, Qualified, Proposal, Closed
            $table->decimal('estimated_value', 10, 2)->nullable();
            $table->date('expected_close_date')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->string('source')->nullable(); // Where the lead came from
            $table->timestamps();
            $table->softDeletes();
        });

        // Create deals table
        Schema::create('crm_deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained('crm_contacts')->onDelete('cascade');
            $table->foreignId('lead_id')->nullable()->constrained('crm_leads')->onDelete('set null');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('stage')->default('Qualified'); // Qualified, Proposal, Negotiation, Closed Won, Closed Lost
            $table->decimal('amount', 10, 2);
            $table->date('expected_close_date');
            $table->date('actual_close_date')->nullable();
            $table->integer('probability')->default(50); // 0-100%
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Create activities table
        Schema::create('crm_activities', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // Task, Call, Meeting, Email
            $table->string('title');
            $table->text('description')->nullable();
            $table->datetime('due_date');
            $table->datetime('completed_at')->nullable();
            $table->string('status')->default('Pending'); // Pending, Completed, Cancelled
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->string('priority')->default('Medium'); // Low, Medium, High
            // Polymorphic relationships to allow activities to be associated with different entities
            $table->morphs('activityable');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Create settings table to store CRM module settings
        Schema::create('crm_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->nullable();
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
        Schema::dropIfExists('crm_activities');
        Schema::dropIfExists('crm_deals');
        Schema::dropIfExists('crm_leads');
        Schema::dropIfExists('crm_contacts');
        Schema::dropIfExists('crm_settings');
    }
}
