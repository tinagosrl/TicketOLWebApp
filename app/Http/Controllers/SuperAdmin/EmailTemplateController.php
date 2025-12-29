<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EmailTemplateController extends Controller
{
    public function index(): View
    {
        $templates = EmailTemplate::all();
        return view('admin.email_templates.index', compact('templates'));
    }

    public function edit(EmailTemplate $emailTemplate): View
    {
        return view('admin.email_templates.edit', ['template' => $emailTemplate]);
    }

    public function update(Request $request, EmailTemplate $emailTemplate): RedirectResponse
    {
        $request->validate([
            'subject_en' => 'required|string|max:255',
            'body_en' => 'required|string',
            'subject_it' => 'nullable|string|max:255',
            'body_it' => 'nullable|string',
        ]);

        $emailTemplate->update($request->all());

        return redirect()->route('admin.email_templates.index')->with('success', 'Template updated successfully.');
    }
}
