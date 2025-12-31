import os 
import glob

# 1. Update Migration
migration_files = glob.glob('database/migrations/*add_allowed_event_types_to_plans_table.php')
if migration_files:
    path = migration_files[0]
    with open(path, 'r') as f:
        content = f.read()
    
    # We will use raw SQL or DB facade to update existing rows in 'up'.
    
    new_content = """<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->json('allowed_event_types')->nullable()->after('features_html');
        });

        // Seed Defaults
        // Starter -> ['scheduled']
        DB::table('plans')->where('slug', 'starter')->update(['allowed_event_types' => json_encode(['scheduled'])]);
        
        // Pro -> ['open']
        DB::table('plans')->where('slug', 'pro')->update(['allowed_event_types' => json_encode(['open'])]);
        
        // Others (Enterprise, Open Access) -> ['scheduled', 'open']
        DB::table('plans')->whereNotIn('slug', ['starter', 'pro'])->update(['allowed_event_types' => json_encode(['scheduled', 'open'])]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('allowed_event_types');
        });
    }
};
"""
    with open(path, 'w') as f:
        f.write(new_content)
    print("Fixed migration file.")

# 2. Update Plan Model
path_model = 'app/Models/Plan.php'
if os.path.exists(path_model):
    with open(path_model, 'r') as f:
        content = f.read()

    # Add 'allowed_event_types' to casts
    if "'allowed_event_types' => 'array'" not in content:
        if "protected $casts = [" in content:
            content = content.replace("protected $casts = [", "protected $casts = [\n        'allowed_event_types' => 'array',")
        else:
             # Add casts block
             pass # Assume it exists or user can add manually. But Plan model usually has casts.
             
    # Add to fillable
    if "'allowed_event_types'" not in content and "protected $fillable = [" in content:
        content = content.replace("protected $fillable = [", "protected $fillable = [\n        'allowed_event_types',")
        
    with open(path_model, 'w') as f:
        f.write(content)
    print("Updated Plan model.")

