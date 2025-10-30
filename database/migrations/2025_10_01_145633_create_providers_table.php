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
            Schema::create('providers', function (Blueprint $table) {
                $table->id(); // PK auto-increment
                $table->string('name');
                $table->string('code_provider')->nullable(); 
                $table->string('rfc');
                $table->string('address');
                $table->string('phone');
                $table->string('email');
                $table->unsignedBigInteger('id_user');          // FK a users
                $table->unsignedBigInteger('person_type_id');   // FK a person_types
                $table->unsignedBigInteger('status_id');        // FK a statuses
                $table->string('url_evidence')->nullable();

                // 👇 Campo JSON que guardará el tipo de proveedor
                $table->json('id_type')->nullable();

                $table->timestamps(); // created_at y updated_at

                // Relación con tabla users
                $table->foreign('id_user')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');

                $table->foreign('status_id')
                    ->references('id')
                    ->on('statuses')
                    ->onDelete('set null');

                $table->foreign('person_type_id')
                  ->references('id') 
                  ->on('person_types')
                  ->onDelete('restrict');


            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('providers');
        }
    };
