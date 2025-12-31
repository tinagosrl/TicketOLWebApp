import os 
import glob

# 1. Update Migration
migration_files = glob.glob('database/migrations/*add_type_to_events_table.php')
if migration_files:
    path = migration_files[0]
    with open(path, 'r') as f:
        content = f.read()
    
    # Add column: $table->enum('type', ['scheduled', 'open'])->default('scheduled')->after('slug');
    target_up = "Schema::table('events', function (Blueprint $table) {\n            //\n        });"
    replacement_up = "Schema::table('events', function (Blueprint $table) {\n            $table->string('type')->default('scheduled')->after('slug'); // 'scheduled' or 'open'\n        });"
    
    target_down = "Schema::table('events', function (Blueprint $table) {\n            //\n        });"
    replacement_down = "Schema::table('events', function (Blueprint $table) {\n            $table->dropColumn('type');\n        });"

    # Since it's a new file, I'll just write the content directly.
    
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
            $table->string('type')->default('scheduled')->after('slug'); 
        });
        
        // Update existing events to 'open' if they are Museum Entry? 
        // User asked for "Museo delle Cere" to have both, but existing ones were Open.
        // But default 'scheduled' is safer.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
"""
    with open(path, 'w') as f:
        f.write(new_content)
    print("Fixed migration file.")

# 2. Update Model
path_model = 'app/Models/Event.php'
if os.path.exists(path_model):
    with open(path_model, 'r') as f:
        content = f.read()

    if "'type'" not in content:
        content = content.replace("'slug',", "'slug',\n        'type',")

    # Add helper method
    if "public function isOpenAccess(): bool" not in content:
        helper = """
    public function isOpenAccess(): bool
    {
        return $this->type === 'open';
    }
    
    public function isScheduled(): bool
    {
        return $this->type === 'scheduled';
    }
"""
        # Insert before closing brace
        last_brace = content.rfind("}")
        content = content[:last_brace] + helper + content[last_brace:]

    with open(path_model, 'w') as f:
        f.write(content)
    print("Updated Event model.")

