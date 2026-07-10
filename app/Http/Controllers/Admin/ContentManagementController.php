<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\CarouselImage;
use App\Models\TeamMember;
use App\Models\Service;
use App\Models\ContactInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContentManagementController extends Controller
{
    /**
     * Dashboard de Conteúdo
     */
    public function index()
    {
        $stats = [
            'carousel_images' => CarouselImage::count(),
            'team_members' => TeamMember::count(),
            'services' => Service::count(),
            'contact_infos' => ContactInfo::count(),
        ];

        return view('admin.content.index', compact('stats'));
    }

    /**
     * ============================================
     * CARROSSEL
     * ============================================
     */
    public function carousel()
    {
        $images = CarouselImage::orderBy('order')->get();
        return view('admin.content.carousel', compact('images'));
    }

    public function storeCarousel(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5048',
            'order' => 'integer',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('carousel', 'public');
        }

        CarouselImage::create([
            'title' => $validated['title'] ?? null,
            'description' => $validated['description'] ?? null,
            'image_path' => $path ?? null,
            'order' => $validated['order'] ?? 0,
            'is_active' => true,
        ]);

        return back()->with('success', 'Imagem do carrossel adicionada!');
    }

    public function updateCarousel(Request $request, $id)
    {
        $image = CarouselImage::findOrFail($id);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5048',
            'order' => 'integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $image->deleteImage();
            $validated['image_path'] = $request->file('image')->store('carousel', 'public');
        }

        $image->update($validated);

        return back()->with('success', 'Imagem atualizada!');
    }

    public function destroyCarousel($id)
    {
        $image = CarouselImage::findOrFail($id);
        $image->deleteImage();
        $image->delete();

        return back()->with('success', 'Imagem removida!');
    }

    /**
     * ============================================
     * EQUIPA MÉDICA
     * ============================================
     */
    public function team()
    {
        $members = TeamMember::orderBy('order')->get();
        return view('admin.content.team', compact('members'));
    }

    public function storeTeam(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'facebook' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'whatsapp' => 'nullable|string',
            'order' => 'integer',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('team', 'public');
        }

        TeamMember::create($validated);

        return back()->with('success', 'Membro da equipa adicionado!');
    }

    public function updateTeam(Request $request, $id)
    {
        $member = TeamMember::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'facebook' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'whatsapp' => 'nullable|string',
            'order' => 'integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            $member->deletePhoto();
            $validated['photo_path'] = $request->file('photo')->store('team', 'public');
        }

        $member->update($validated);

        return back()->with('success', 'Membro atualizado!');
    }

    public function destroyTeam($id)
    {
        $member = TeamMember::findOrFail($id);
        $member->deletePhoto();
        $member->delete();

        return back()->with('success', 'Membro removido!');
    }

    /**
     * ============================================
     * SERVIÇOS
     * ============================================
     */
    public function services()
    {
        $services = Service::orderBy('order')->get();
        return view('admin.content.services', compact('services'));
    }

    public function storeService(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'string|max:50',
            'color' => 'string|max:50',
            'order' => 'integer',
        ]);

        Service::create($validated);

        return back()->with('success', 'Serviço adicionado!');
    }

    public function updateService(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $service->update($request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'string|max:50',
            'color' => 'string|max:50',
            'order' => 'integer',
            'is_active' => 'boolean',
        ]));

        return back()->with('success', 'Serviço atualizado!');
    }

    public function destroyService($id)
    {
        Service::findOrFail($id)->delete();
        return back()->with('success', 'Serviço removido!');
    }

    /**
     * ============================================
     * CONTACTOS
     * ============================================
     */
    public function contacts()
    {
        $contacts = ContactInfo::orderBy('type')->orderBy('order')->get();
        return view('admin.content.contacts', compact('contacts'));
    }

    public function storeContact(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:address,phone,email,hours',
            'label' => 'nullable|string|max:255',
            'value' => 'required|string',
            'order' => 'integer',
        ]);

        ContactInfo::create($validated);

        return back()->with('success', 'Informação de contacto adicionada!');
    }

    public function updateContact(Request $request, $id)
    {
        $contact = ContactInfo::findOrFail($id);
        $contact->update($request->validate([
            'type' => 'required|in:address,phone,email,hours',
            'label' => 'nullable|string|max:255',
            'value' => 'required|string',
            'order' => 'integer',
            'is_active' => 'boolean',
        ]));

        return back()->with('success', 'Contacto atualizado!');
    }

    public function destroyContact($id)
    {
        ContactInfo::findOrFail($id)->delete();
        return back()->with('success', 'Contacto removido!');
    }

    /**
 * ============================================
 * SOBRE NÓS (CORRIGIDO)
 * ============================================
 */
public function about()
{
    // Obter todas as configurações existentes
    $settings = SiteSetting::all()->pluck('value', 'key')->toArray();
    
    return view('admin.content.about', compact('settings'));
}

public function updateAbout(Request $request)
{
    // Lista de todos os campos que podem ser atualizados
    $fields = [
        'about_title',
        'about_subtitle',
        'about_paragraph_1',
        'about_paragraph_2',
        'about_card_number',
        'about_card_text',
        'feature_1_title',
        'feature_1_desc',
        'feature_2_title',
        'feature_2_desc',
        'feature_3_title',
        'feature_3_desc',
        'feature_4_title',
        'feature_4_desc',
    ];

    // Atualizar cada campo
    foreach ($fields as $field) {
        $value = $request->input($field);
        if ($value !== null) {
            SiteSetting::set($field, $value);
        }
    }

    return back()->with('success', '✅ Secção "Sobre Nós" atualizada com sucesso!');
}

    /**
     * ============================================
     * CONFIGURAÇÕES GERAIS
     * ============================================
     */
    public function settings()
    {
        $settings = SiteSetting::all()->pluck('value', 'key')->toArray();
        return view('admin.content.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_slogan' => 'nullable|string|max:255',
            'phone_main' => 'nullable|string',
            'email_main' => 'nullable|email',
            'address' => 'nullable|string',
            'whatsapp_number' => 'nullable|string',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
        ]);

        foreach ($validated as $key => $value) {
            SiteSetting::set($key, $value);
        }

        return back()->with('success', 'Configurações atualizadas!');
    }

    /**
     * Upload da imagem "Sobre Nós"
     */
    public function uploadAboutImage(Request $request)
    {
        $request->validate([
            'about_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Apagar imagem anterior se existir
        $oldPath = SiteSetting::get('about_image_path');
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        // Salvar nova imagem
        $path = $request->file('about_image')->store('about', 'public');
        SiteSetting::set('about_image_path', $path);

        return back()->with('success', 'Imagem "Sobre Nós" atualizada!');
    }
}