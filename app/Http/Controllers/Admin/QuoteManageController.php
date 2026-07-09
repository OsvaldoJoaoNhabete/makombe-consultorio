<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use Illuminate\Http\Request;

class QuoteManageController extends Controller
{
    public function index()
    {
        $quotes = Quote::with(['patient', 'insurance'])->orderByDesc('created_at')->paginate(15);
        return view('admin.quotes.index', compact('quotes'));
    }

    public function create()
    {
        return view('admin.quotes.form');
    }

    public function store(Request $request)
    {
        return back()->with('info', 'Funcionalidade em desenvolvimento.');
    }

    public function show($id)
    {
        $quote = Quote::with(['patient', 'insurance'])->findOrFail($id);
        return view('admin.quotes.show', compact('quote'));
    }

    public function edit($id)
    {
        $quote = Quote::findOrFail($id);
        return view('admin.quotes.form', compact('quote'));
    }

    public function update(Request $request, $id)
    {
        return back()->with('info', 'Funcionalidade em desenvolvimento.');
    }

    public function approve($id)
    {
        Quote::findOrFail($id)->update(['status' => 'aprovada']);
        return back()->with('success', 'Cotação aprovada.');
    }

    public function reject($id)
    {
        Quote::findOrFail($id)->update(['status' => 'recusada']);
        return back()->with('success', 'Cotação recusada.');
    }

    public function send($id)
    {
        Quote::findOrFail($id)->update(['status' => 'enviada']);
        return back()->with('success', 'Cotação enviada.');
    }

    public function destroy($id)
    {
        Quote::findOrFail($id)->delete();
        return back()->with('success', 'Cotação excluída.');
    }

    public function print($id)
    {
        return back()->with('info', 'Funcionalidade em desenvolvimento.');
    }
}