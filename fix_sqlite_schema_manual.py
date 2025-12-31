import sqlite3

db_path = 'database/database.sqlite'
conn = sqlite3.connect(db_path)
c = conn.cursor()

try:
    # 1. Rename
    c.execute("ALTER TABLE events RENAME TO events_old")
    
    # 2. Create New
    # Note: `vertical_image_path` added
    schema = """
    CREATE TABLE "events" (
        "id" integer primary key autoincrement not null, 
        "tenant_id" integer not null, 
        "venue_id" integer not null, 
        "name" varchar not null, 
        "slug" varchar not null, 
        "description" text, 
        "start_date" datetime not null, 
        "end_date" datetime, 
        "image_path" varchar, 
        "vertical_image_path" varchar DEFAULT NULL,
        "is_published" tinyint(1) not null default '0', 
        "created_at" datetime, 
        "updated_at" datetime, 
        "type" varchar not null default 'scheduled',
        foreign key("tenant_id") references "tenants"("id") on delete cascade, 
        foreign key("venue_id") references "venues"("id") on delete cascade
    );
    """
    c.execute(schema)
    c.execute('CREATE UNIQUE INDEX "events_tenant_id_slug_unique" on "events" ("tenant_id", "slug");')
    
    # 3. Copy Data
    # vertical_image_path is new, so we don't select it from old.
    cols = "id, tenant_id, venue_id, name, slug, description, start_date, end_date, image_path, is_published, created_at, updated_at, type"
    # Note: end_date in old might be valid, so we copy it.
    
    c.execute(f"INSERT INTO events ({cols}) SELECT {cols} FROM events_old")
    
    # 4. Drop Old
    c.execute("DROP TABLE events_old")
    
    conn.commit()
    print("Schema updated successfully.")
except Exception as e:
    conn.rollback()
    print(f"Error: {e}")
finally:
    conn.close()

