<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignRoleCommand extends Command
{
    protected $signature = 'user:assign-role {user_id} {role_name}';
    protected $description = 'Atribui uma função (Role) a um utilizador usando o Spatie Permission';

    public function handle(): int
    {
        $userId = $this->argument('user_id');
        $roleName = $this->argument('role_name');

        // 1. Buscar o utilizador
        $user = User::find($userId);
        if (!$user) {
            $this->error("❌ Utilizador com ID {$userId} não encontrado.");
            return Command::FAILURE;
        }

        // 2. Buscar ou criar a função (Role) usando o Spatie
        $role = Role::firstOrCreate(['name' => $roleName]);

        // 3. Atribuir a função ao utilizador (método mágico do Spatie)
        if ($user->hasRole($roleName)) {
            $this->warn("⚠️  O utilizador '{$user->name}' já possui a função '{$roleName}'.");
        } else {
            $user->assignRole($roleName);
            $this->info("✅ Sucesso! A função '{$roleName}' foi atribuída ao utilizador '{$user->name}'.");
        }

        // 4. Bónus: Também atualiza a coluna 'role' direta na tabela users (se existir)
        if (\Schema::hasColumn('users', 'role')) {
            $user->role = $roleName;
            $user->save();
            $this->info("📝 Coluna 'role' da tabela users também atualizada.");
        }

        return Command::SUCCESS;
    }
}