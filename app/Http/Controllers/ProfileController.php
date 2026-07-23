<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Mostra o formulário de edição do perfil do utilizador.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Atualiza as informações do perfil do utilizador.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // Validação dos campos
        $rules = [
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ];

        // Se o utilizador preencheu o campo de password, validamos também
        if ($request->filled('password')) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }

        // Valida os dados
        $validated = $request->validate($rules, [
            'name.required' => 'O nome é obrigatório.',
            'name.min' => 'O nome deve ter pelo menos 3 caracteres.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.unique' => 'Este e-mail já está em uso.',
            'photo.image' => 'O ficheiro deve ser uma imagem.',
            'photo.mimes' => 'A imagem deve ser JPG, PNG, GIF ou WEBP.',
            'photo.max' => 'A imagem não pode exceder 2MB.',
            'password.min' => 'A palavra-passe deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'As palavras-passe não coincidem.',
        ]);

        // Gestão da foto
        if ($request->hasFile('photo')) {
            // Remover foto antiga se existir
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            
            $file = $request->file('photo');
            $fileName = time() . '_' . str_replace(' ', '_', $user->name) . '.' . $file->getClientOriginalExtension();
            $validated['photo'] = $file->storeAs('users/photos', $fileName, 'public');
        }

        // Atualiza os dados básicos
        $user->name = trim($validated['name']);
        $user->email = strtolower(trim($validated['email']));
        $user->phone = $validated['phone'] ?? null;
        
        // Atualiza a foto se foi enviada
        if (isset($validated['photo'])) {
            $user->photo = $validated['photo'];
        }
        
        // Atualiza a password apenas se foi fornecida
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return Redirect::route('profile.edit')->with('success', 'Perfil atualizado com sucesso!');
    }
}