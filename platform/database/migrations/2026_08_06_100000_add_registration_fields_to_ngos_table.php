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
        Schema::table('ngos', function (Blueprint $table) {
            $table->string('reference_number')->nullable()->unique()->after('id');
            $table->string('abbreviation')->nullable()->after('name');
            $table->string('registration_number')->nullable()->unique()->after('slug');
            $table->string('fiscal_number')->nullable()->after('registration_number');
            $table->string('primary_community')->nullable()->after('category');
            $table->json('additional_communities')->nullable()->after('primary_community');
            $table->string('responsible_person')->nullable()->after('contact_phone');
            // The registration form collects the responsible person's phone and e-mail as
            // one free-text field; contact_email / contact_phone stay for the public profile.
            $table->text('responsible_person_contact')->nullable()->after('responsible_person');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved')->after('is_active');
            $table->text('review_notes')->nullable()->after('status');
            // An immutable copy of the application exactly as it was submitted. The columns
            // above are the live record and staff may correct them; this is the evidence.
            $table->json('submitted_data')->nullable()->after('review_notes');
            // Evidence that the applicant attested to accuracy and authority to represent
            // the organization — an anti-fraud declaration, not a data-protection consent.
            $table->timestamp('declared_at')->nullable()->after('review_notes');
            $table->timestamp('submitted_at')->nullable()->after('declared_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ngos', function (Blueprint $table) {
            $table->dropUnique(['reference_number']);
            $table->dropUnique(['registration_number']);
            $table->dropColumn([
                'reference_number', 'abbreviation', 'registration_number', 'fiscal_number',
                'primary_community', 'additional_communities', 'responsible_person',
                'responsible_person_contact', 'status', 'review_notes', 'submitted_data',
                'declared_at', 'submitted_at',
            ]);
        });
    }
};
