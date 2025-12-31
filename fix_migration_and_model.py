import os 
import glob

# 1. Fix Migration
migration_files = glob.glob('database/migrations/*add_opening_hours_to_venues_table.php')
if migration_files:
    path = migration_files[0]
    with open(path, 'r') as f:
        content = f.read()
    
    # Needs Up
    target_up = "Schema::table('venues', function (Blueprint $table) {\n            //\n        });"
    replacement_up = "Schema::table('venues', function (Blueprint $table) {\n            $table->json('opening_hours')->nullable()->after('address');\n        });"
    
    # Needs Down
    target_down = "Schema::table('venues', function (Blueprint $table) {\n            //\n        });"
    replacement_down = "Schema::table('venues', function (Blueprint $table) {\n            $table->dropColumn('opening_hours');\n        });"

    # Replace first occurrence (up) then second (down)? Or safer search?
    # Usually Up is first.
    if target_up in content:
        content = content.replace(target_up, replacement_up, 1) # Replace Up
        content = content.replace(target_up, replacement_down, 1) # Replace Down (which is now still target_up text in original logic if I didn't verify... wait)
        # Actually identifying Up vs Down is better by function name
    else:
        # Fallback replace based on simple string inside method
        if "//" in content:
           # Replace first // with column add, second with column drop? Risky.
           # Let's simple write the file content if we are sure.
           pass 
           
    # Let's just overwrite the file content with the correct template since it's a new migration file I just made.
    
    new_migration_content = """<?php

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
        Schema::table('venues', function (Blueprint $table) {
            $table->json('opening_hours')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('venues', function (Blueprint $table) {
            $table->dropColumn('opening_hours');
        });
    }
};
"""
    with open(path, 'w') as f:
        f.write(new_migration_content)
    print("Fixed migration file.")

# 2. Fix Model
path_model = 'app/Models/Venue.php'
if os.path.exists(path_model):
    with open(path_model, 'r') as f:
        content = f.read()

    # Add 'opening_hours' to fillable
    if "'opening_hours'" not in content and "protected $fillable = [" in content:
        content = content.replace("protected $fillable = [", "protected $fillable = [\n        'opening_hours',")

    # Add cast
    if "protected $casts =" not in content:
        # insert before end of class
        last_brace = content.rfind("}")
        casts_code = "\n    protected $casts = [\n        'opening_hours' => 'array',\n    ];\n"
        content = content[:last_brace] + casts_code + content[last_brace:]
    
    with open(path_model, 'w') as f:
        f.write(content)
    print("Fixed Venue model.")

