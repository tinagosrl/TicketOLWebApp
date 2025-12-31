import os 

path = 'routes/web.php'
if os.path.exists(path):
    with open(path, 'r') as f:
        content = f.read()

    # Fix: ->name('admin.logs.impersonation') -> ->name('logs.impersonation')
    # Fix: ->name('admin.tenants.impersonate') -> ->name('tenants.impersonate')
    
    new_content = content.replace("->name('admin.logs.impersonation')", "->name('logs.impersonation')")
    new_content = new_content.replace("->name('admin.tenants.impersonate')", "->name('tenants.impersonate')")
    
    if new_content != content:
        with open(path, 'w') as f:
            f.write(new_content)
        print("Fixed route names in web.php")
    else:
        print("No changes made (maybe already fixed or pattern mismatch).")

