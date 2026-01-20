    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class() extends Migration {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            Schema::create(config('ltools.table_name_changelog_items'), function (Blueprint $table) {
                $table->id();
                $table->string('model', 191);
                $table->string('model_id', 191);
                $table->json('changes');
                $table->bigInteger('user_id')->nullable();
                $table->uuid('uuid')->unique();
                $table->timestamp('created_at')->useCurrent();
                $table->index(['model', 'model_id'], 'IDX_model__model_id');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists(config('ltools.table_name_changelog_items'));
        }
    };
