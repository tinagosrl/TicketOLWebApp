import os 
import glob

# 1. Update Migration
migration_files = glob.glob('database/migrations/*make_end_date_nullable_in_events_table.php')
if migration_files:
    path = migration_files[0]
    new_content = """<?php

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
        Schema::table('events', function (Blueprint $table) {
            $table->datetime('end_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->datetime('end_date')->nullable(false)->change();
        });
    }
};
"""
    # Note: SQLite doesn't support ->change() easily with Doctrine usually.
    # Laravel needs doctrine/dbal for this. And SQLite has limited alter support.
    # If this fails, I might need to ignore it or use raw SQL to create new table and copy (complex).
    # Or simplified: Just remove not null constraint?
    # Actually SQLite ->change() often fails.
    # But let's try. If fails, I will use sqlite3 directly to modify schema (which effectively means recreating table usually).
    # Wait, SQLite 3.35+ supports DROP COLUMN etc, but ALTER COLUMN NULLABILITY is hard.
    # I'll try the migration first.

    with open(path, 'w') as f:
        f.write(new_content)
    print("Fixed migration file.")

# 2. Update Controller Validation
path_controller = 'app/Http/Controllers/Tenant/EventController.php'
if os.path.exists(path_controller):
    with open(path_controller, 'r') as f:
        content = f.read()

    # Rule: 'end_date' => 'required|date|after:start_date',
    # We want to change to conditional.
    # $rules = [ ... ];
    # if ($request->type == 'scheduled') { $rules['end_date'] = 'required...'; } else { $rules['end_date'] = 'nullable...'; }
    
    # This requires refactoring the validate call.
    # public function store(Request $request) { $request->validate([...]); }
    
    # I will replace the validate call with a more complex block.
    
    # Store Method
    store_start = "public function store(Request $request): RedirectResponse"
    
    # This is getting complex to regex replace correctly.
    # Let's search for the validation array inside store.
    
    # 'end_date' => 'required|date|after:start_date',
    
    if "'end_date' => 'required|date|after:start_date'," in content:
        # We replace it with 'end_date' => 'nullable|date|after:start_date',
        # And rely on "required_if:type,scheduled"?
        # 'end_date' => 'required_if:type,scheduled|nullable|date|after:start_date',
        
        replacement = "'end_date' => 'required_if:type,scheduled|nullable|date|after:start_date',"
        content = content.replace("'end_date' => 'required|date|after:start_date',", replacement)
        
        with open(path_controller, 'w') as f:
             f.write(content)
        print("Updated validation rules in EventController.")

