<?php
$newEmail = 'ranelaesgana@gmail.com';
$oldEmail = 'admin@example.com';

$user = App\Models\User::where('email', $newEmail)->first();

if ($user) {
    $user->role = 'admin';
    $user->save();
    echo "Existing user promoted to admin.\n";
} else {
    $oldUser = App\Models\User::where('email', $oldEmail)->first();
    if ($oldUser) {
        $oldUser->email = $newEmail;
        $oldUser->save();
        echo "Old admin email updated to new email.\n";
    } else {
        App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => $newEmail,
            'role' => 'admin',
            'password' => bcrypt('password'), 
        ]);
        echo "New admin user created.\n";
    }
}
exit(0);
