<?php

use Illuminate\Support\Facades\Route;

$availableLanguages = Config::get('app.available_locales');
$lang = Request::getPreferredLanguage($availableLanguages);
if ($lang) Config::set('app.locale', $lang);

Route::middleware(['auth'])->group(function () {
    Route::get('/', \App\Livewire\SelectCommittee::class)->name('select-committee');

    Route::get('/calendar', \App\Livewire\Calendar::class)->name('calendar');

    Route::get('/committees', \App\Livewire\Committees\Committees::class)->name('committees');
    Route::get('/committees/new', \App\Livewire\Committees\AddCommittee::class)->name('committee.new')->can('admin');
    Route::get('/committees/{committee}/general', \App\Livewire\Committees\EditCommittee::class)->name('committee.edit')->can('admin');

    Route::get('/committees/{committee}/departments', \App\Livewire\Committees\Departments::class)->name('departments')->can('admin');
    Route::get('/committees/{committee}/goals', \App\Livewire\Committees\Goals::class)->name('goals')->can('admin');
    Route::get('/committees/{committee}/groups', \App\Livewire\Committees\Groups::class)->name('groups')->can('admin');
    Route::get('/committees/{committee}/members', \App\Livewire\Committees\Members::class)->name('committee.members')->can('admin');
    Route::get('/committees/{committee}/templates', \App\Livewire\Committees\Templates::class)->name('templates')->can('admin');
    Route::get('/committees/{committee}/wiki', \App\Livewire\Committees\Wiki::class)->name('committee.wiki')->can('admin');

    Route::get('/terms', \App\Livewire\Terms\Terms::class)->name('terms');
    Route::get('/terms/new', \App\Livewire\Terms\AddTerm::class)->name('term.add')->can('admin');
    Route::get('/terms/{number}/edit', \App\Livewire\Terms\EditTerm::class)->name('term.edit')->can('admin');

    Route::get('/{committee}/members/new', \App\Livewire\Members\AddMember::class)->name('member.add')->can('admin');
    Route::get('/{committee}/members/{id}/edit', \App\Livewire\Members\EditMember::class)->name('member.edit')->can('admin');

    Route::get('/{committee}/resolutions', \App\Livewire\Resolutions\Resolutions::class)->name('resolutions');
    Route::get('/{committee}/resolutions/new', \App\Livewire\Resolutions\AddResolution::class)->name('resolution.new');
    Route::get('/{committee}/resolutions/{id}/edit', \App\Livewire\Resolutions\EditResolution::class)->name('resolution.edit');

    Route::get('/{committee}', \App\Livewire\Dashboard::class)->name('dashboard');
    Route::get('/{committee}/meetings/{meeting}/agenda', \App\Livewire\Agenda\Agenda::class)->name('agenda');
    Route::get('/{committee}/meetings', \App\Livewire\Meetings\Meetings::class)->name('meetings');
    Route::get('/{committee}/members', \App\Livewire\Members\Members::class)->name('members');
});

//Route::middleware(['committeeMember'])->group(function () {
Route::middleware(['auth'])->group(function () {
    Route::get('/{committee}/meetings/new', \App\Livewire\Meetings\NewMeeting::class)->name('meeting.new');
    Route::get('/{committee}/meetings/{meeting}/edit', \App\Livewire\Meetings\EditMeeting::class)->name('meeting.edit');

    Route::get('/{committee}/meetings/{meeting}/agenda/item/new', \App\Livewire\Agenda\AddItem::class)->name('agenda-item.new');
    Route::get('/{committee}/meetings/{meeting}/agenda/item/{id}', \App\Livewire\Agenda\ViewItem::class)->name('agenda-item.view');
    Route::get('/{committee}/meetings/{meeting}/agenda/item/{id}/edit', \App\Livewire\Agenda\EditItem::class)->name('agenda-item.edit');

    Route::get('/{committee}/meetings/{meeting}/agenda/item/{item}/motions', \App\Livewire\Motions\Motions::class)->name('motions');
    Route::get('/{committee}/meetings/{meeting}/agenda/item/{item}/motions/{id}/edit', \App\Livewire\Motions\EditMotion::class)->name('motion.edit');

    Route::get('/{committee}/meetings/{meeting}/agenda/item/{item}/attachments', \App\Livewire\Attachments\Attachments::class)->name('attachments');
});

/*
Route::get('/imprint', function () {
    return redirect(config('app.imprint_url'));
})->name('imprint');

Route::get('/privacy', function () {
    return redirect(config('app.privacy_url'));
})->name('privacy');

Route::get('/accessibility', function () {
    return redirect(config('app.accessibility_url'));
})->name('accessibility');

Route::get('/source-code', function () {
    return redirect(config('app.source_code_url'));
})->name('source-code');

Route::get('/contributors', function () {
    return redirect(config('app.contributors_url'));
})->name('contributors');

Route::get('/translate', function () {
    return redirect(config('app.translate_url'));
})->name('translate');
*/

require __DIR__.'/auth.php';
