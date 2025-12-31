import os 

path = 'resources/views/admin/tenants/index.blade.php'
if os.path.exists(path):
    with open(path, 'r') as f:
        content = f.read()

    # 1. Update Domain Link (add route or fix href)
    # Old: <a href="http://{{ $tenant->domain }}" ...>
    # New: <a href="{{ route('public.shop.index', ['domain' => $tenant->domain]) }}" ...>
    
    if '<a href="http://{{ $tenant->domain }}"' in content:
        content = content.replace(
            '<a href="http://{{ $tenant->domain }}"',
            '<a href="{{ route(\'public.shop.index\', [\'domain\' => $tenant->domain]) }}"'
        )

    # 2. Add Impersonate Button in Actions
    # Look for Edit Button or Delete Form
    # <a href="{{ route('admin.tenants.edit', $tenant->id) }}"
    
    impersonate_btn = """<a href="{{ route('admin.tenants.impersonate', $tenant->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3" title="Login as Admin">Impersonate</a>"""
    
    if 'class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>' in content and 'Impersonate' not in content:
        content = content.replace(
            'class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>',
            impersonate_btn + '\n                                            <a href="{{ route(\'admin.tenants.edit\', $tenant->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>'
        )
        
    with open(path, 'w') as f:
        f.write(content)
    print("Updated admin/tenants/index.blade.php")

