<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('seo_metadata')) {
            return;
        }

        Schema::table('seo_metadata', function (Blueprint $table) {
            if (! Schema::hasColumn('seo_metadata', 'twitter_image')) {
                $table->string('twitter_image')->nullable()->after('twitter_description');
            }

            if (! Schema::hasColumn('seo_metadata', 'og_image_alt')) {
                $table->string('og_image_alt')->nullable()->after('og_image');
            }

            if (! Schema::hasColumn('seo_metadata', 'sitemap_priority')) {
                $table->decimal('sitemap_priority', 2, 1)->nullable()->after('score');
            }

            if (! Schema::hasColumn('seo_metadata', 'sitemap_changefreq')) {
                $table->string('sitemap_changefreq', 20)->nullable()->after('sitemap_priority');
            }

            if (! Schema::hasColumn('seo_metadata', 'noindex_reason')) {
                $table->string('noindex_reason')->nullable()->after('robots_follow');
            }

            if (! Schema::hasColumn('seo_metadata', 'last_audited_at')) {
                $table->timestamp('last_audited_at')->nullable()->after('sitemap_changefreq');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('seo_metadata')) {
            return;
        }

        Schema::table('seo_metadata', function (Blueprint $table) {
            $columns = collect([
                'og_image_alt',
                'sitemap_priority',
                'sitemap_changefreq',
                'noindex_reason',
                'last_audited_at',
            ])->filter(fn (string $column) => Schema::hasColumn('seo_metadata', $column))->all();

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
