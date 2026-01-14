# Role System Explanation

## Overview
Your application currently has **TWO separate role systems** running in parallel:

---

## 1. Legacy Static Role System

### How it Works:
- Uses the `role` column in the `users` table (VARCHAR field)
- Stores a **single, static role** per user (e.g., 'student', 'admin', 'user')
- Simple string comparison: `Auth::user()->role == 'student'`
- **Not tied to any website** - same role across all websites

### When to Use:
✅ For basic role checks (like showing/hiding buttons based on user type)
✅ For simple authentication and authorization
✅ When you need a quick role check without database queries
✅ For backward compatibility with existing code

### Example Usage:
```blade
@if(Auth::user()->role == 'student')
    <button>View Profile</button>
@endif
```

```php
if (Auth::user()->role === 'admin') {
    // Admin logic
}
```

---

## 2. New Dynamic Role-Permission System

### How it Works:
- Uses **THREE tables**: `roles`, `permissions`, `role_user_website` (pivot)
- Allows **multiple roles per user PER website**
- Each role can have **multiple permissions**
- **Website-scoped**: Same user can have different roles on different websites
- More flexible and granular control

### Database Structure:
```
users
├── id
└── role (legacy column)

roles
├── id
├── name ('admin', 'editor', 'viewer')
└── label ('Administrator', 'Content Editor', 'Viewer')

permissions
├── id
├── name ('view_users', 'create_users', 'delete_users')
├── label
└── group ('user_management', 'analytics', etc.)

role_user_website (pivot)
├── user_id
├── role_id
└── website_id (makes roles website-specific!)

permission_role (pivot)
├── permission_id
└── role_id
```

### When to Use:
✅ For **complex permission systems** (can a user edit users? create roles?)
✅ For **multi-website applications** (different roles per website)
✅ When you need **granular control** over what users can do
✅ For the **User Management** section (Users, Roles, Permissions CRUD)

### Example Usage:
```php
// Check if user has a specific role for current website
if (Auth::user()->hasRoleForWebsite('admin', $websiteId)) {
    // Admin logic for this website
}

// Check if user has a specific permission
if (Auth::user()->hasPermission('create_users')) {
    // Show create user button
}

// Assign role to user for specific website
$user->assignRoleForWebsite('editor', $websiteId);

// Sync multiple roles for a website (removes old ones)
$user->syncRolesForWebsite(['editor', 'viewer'], $websiteId);
```

```blade
@if(Auth::user()->hasRoleForWebsite('admin', Auth::user()->website_id))
    <div class="admin-panel">
        <!-- Admin controls -->
    </div>
@endif

@if(Auth::user()->hasPermission('view_analytics'))
    <a href="/analytics">View Analytics</a>
@endif
```

---

## Current Implementation in Your Code

### Profile Page View/Share Buttons
**Currently using: Legacy Static Role System**

```blade
@if(Auth::user()->role == 'student')
    <div class="btn-group">
        <a href="/profile/...">View</a>
        <button>Share</button>
    </div>
@endif
```

**Why this approach?**
- Simple and fast
- Doesn't require additional database queries
- Sufficient for basic UI visibility control
- The legacy `role` column already exists and is being used

### User Management Section
**Uses: New Dynamic Role-Permission System**

```php
// UserManagementController.php
public function store(Request $request)
{
    $user = User::create([...]);
    
    // Assign roles to user for current website
    if ($request->roles) {
        foreach ($request->roles as $roleId) {
            $user->roles()->attach($roleId, [
                'website_id' => Auth::user()->website_id
            ]);
        }
    }
}
```

**Why this approach?**
- Allows multiple roles per user
- Website-scoped (user can be 'admin' on Website A, 'viewer' on Website B)
- Each role has specific permissions
- More professional and scalable for complex applications

---

## Migration Strategy (Optional)

If you want to **fully migrate** from legacy to new system:

### Option 1: Keep Both Systems (Recommended)
- Use **legacy `role` column** for simple checks (UI visibility, basic auth)
- Use **dynamic role system** for complex permission management
- No migration needed - both work together

### Option 2: Full Migration to Dynamic System
1. Create roles matching legacy values:
   ```sql
   INSERT INTO roles (name, label) VALUES
   ('student', 'Student'),
   ('admin', 'Administrator'),
   ('user', 'User');
   ```

2. Migrate existing users to new system:
   ```php
   $users = User::all();
   foreach ($users as $user) {
       if ($user->role) {
           $role = Role::where('name', $user->role)->first();
           if ($role) {
               $user->assignRoleForWebsite($role->name, $user->website_id);
           }
       }
   }
   ```

3. Update all role checks:
   ```php
   // OLD: Auth::user()->role == 'student'
   // NEW: Auth::user()->hasRoleForWebsite('student', $websiteId)
   ```

4. (Optional) Drop the `role` column from `users` table:
   ```php
   Schema::table('users', function (Blueprint $table) {
       $table->dropColumn('role');
   });
   ```

---

## Best Practices

### ✅ DO:
- Use legacy `role` for **simple checks** (showing/hiding buttons)
- Use dynamic system for **permission management** (what can user do?)
- Keep roles **website-scoped** when using dynamic system
- Cache permission checks if checking frequently
- Document which system is used where

### ❌ DON'T:
- Mix both systems for the same check (confusing)
- Check permissions on every request without caching
- Forget to scope roles by website in dynamic system
- Delete legacy `role` column if code still depends on it

---

## Summary Table

| Feature | Legacy Static System | New Dynamic System |
|---------|---------------------|-------------------|
| **Storage** | `users.role` (VARCHAR) | `role_user_website` pivot |
| **Complexity** | Simple string | Roles + Permissions |
| **Website Scope** | ❌ No | ✅ Yes |
| **Multiple Roles** | ❌ No (one role) | ✅ Yes (many roles) |
| **Permissions** | ❌ No | ✅ Yes (35+ permissions) |
| **Performance** | ⚡ Fast (no joins) | 🐌 Slower (requires joins) |
| **Use Case** | Basic auth, UI visibility | Complex RBAC, admin panels |
| **Example** | `role == 'student'` | `hasRoleForWebsite('admin', 1)` |

---

## Your Current Setup

✅ **Profile page** → Using **legacy system** (`role == 'student'`)
✅ **User Management** → Using **dynamic system** (role_user_website pivot)
✅ **35 Permissions** → Created and seeded successfully
✅ **Website Filtering** → Users scoped to current website
✅ **Both systems** → Working together without conflict

**No migration needed** - your current hybrid approach is perfectly valid! 🎉
