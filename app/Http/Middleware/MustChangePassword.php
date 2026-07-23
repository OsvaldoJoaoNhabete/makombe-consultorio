<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MustChangePassword
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar se o utilizador está autenticado
        if (auth()->check()) {
            $user = auth()->user();
            
            // Verificar se a flag must_change_password está ativa (true ou 1)
            if ($user->must_change_password == true) {
                // Lista de rotas permitidas sem redirecionamento
                $allowedRoutes = [
                    'first-login.show',
                    'first-login.update',
                    'logout',
                    'staff.logout',
                ];
                
                // Obter o nome da rota atual
                $currentRoute = $request->route() ? $request->route()->getName() : null;
                
                // Se a rota atual NÃO está na lista de permitidas, redireciona
                if (!in_array($currentRoute, $allowedRoutes)) {
                    return redirect()->route('first-login.show')
                        ->with('warning', 'Por segurança, é obrigatório alterar a sua palavra-passe no primeiro acesso.');
                }
            }
        }

        return $next($request);
    }
}