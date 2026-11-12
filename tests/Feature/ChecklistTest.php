<?php

use App\Events\ChecklistUpdatedEvent;
use App\Models\Checklist;
use App\Models\User;
use Illuminate\Support\Facades\Event;

function checklistFor(User $user, string $theme = 'enshrouded'): Checklist
{
    $checklist = new Checklist(['name' => 'Tonight', 'theme' => $theme, 'top' => 10, 'left' => 20, 'width' => 500]);
    $checklist->user_id = $user->id;
    $checklist->save();
    $checklist->items()->create(['text' => 'Find the Flame Altar', 'position' => 1]);
    $checklist->items()->create(['text' => 'Beat the Fell Thunderbrute', 'position' => 2]);

    return $checklist;
}

test('ticking an item updates the overlay', function () {
    Event::fake([ChecklistUpdatedEvent::class]);
    $user = User::factory()->create();
    $checklist = checklistFor($user);
    $second = $checklist->items()->where('position', 2)->first();

    $this->actingAs($user)
        ->patch(route('checklists.items.toggle', [$checklist->id, $second->id]))
        ->assertRedirect(route('checklists.show', $checklist->id));

    Event::assertDispatched(ChecklistUpdatedEvent::class, fn ($event) => $event->action === 'done'
        && $event->itemId === $second->id
        && $event->broadcastOn()->name === 'overlay.' . $user->id);

    $html = $this->get(route('overlay.checklist', [$user->username, $checklist->id]))
        ->assertOk()
        ->assertSee('1</b> of 2 complete', false)
        ->getContent();
    expect($html)->toMatch('/data-item-id="' . $second->id . '" class="is-done"/')
        ->not->toMatch('/data-item-id="' . ($second->id - 1) . '" class="is-done"/');

    $this->actingAs($user)->patch(route('checklists.items.toggle', [$checklist->id, $second->id]));
    expect($second->fresh()->done)->toBeFalse();
    Event::assertDispatched(ChecklistUpdatedEvent::class, fn ($event) => $event->action === 'undone');
});

test('moving an item reorders the overlay', function () {
    Event::fake([ChecklistUpdatedEvent::class]);
    $user = User::factory()->create();
    $checklist = checklistFor($user);
    $last = $checklist->items()->create(['text' => 'Craft a Glider', 'position' => 3]);
    $first = $checklist->items()->where('position', 1)->first();

    $this->actingAs($user)->patch(route('checklists.items.move', [$checklist->id, $last->id]), ['direction' => 'up']);
    $this->get(route('overlay.checklist', [$user->username, $checklist->id]))
        ->assertSeeInOrder(['Find the Flame Altar', 'Craft a Glider', 'Beat the Fell Thunderbrute']);
    Event::assertDispatched(ChecklistUpdatedEvent::class, fn ($event) => $event->action === 'moved' && $event->itemId === $last->id);

    $this->actingAs($user)->patch(route('checklists.items.move', [$checklist->id, $first->id]), ['direction' => 'down']);
    $this->get(route('overlay.checklist', [$user->username, $checklist->id]))
        ->assertSeeInOrder(['Craft a Glider', 'Find the Flame Altar', 'Beat the Fell Thunderbrute']);

    // Moving the top item up changes nothing and tells the overlay nothing.
    Event::fake([ChecklistUpdatedEvent::class]);
    $this->actingAs($user)->patch(route('checklists.items.move', [$checklist->id, $last->id]), ['direction' => 'up']);
    $this->get(route('overlay.checklist', [$user->username, $checklist->id]))
        ->assertSeeInOrder(['Craft a Glider', 'Find the Flame Altar', 'Beat the Fell Thunderbrute']);
    Event::assertNotDispatched(ChecklistUpdatedEvent::class);
});

test('another user cannot change a checklist', function () {
    $owner = User::factory()->create();
    $checklist = checklistFor($owner);
    $item = $checklist->items()->first();

    $this->actingAs(User::factory()->create())
        ->patch(route('checklists.items.toggle', [$checklist->id, $item->id]))
        ->assertNotFound();

    expect($item->fresh()->done)->toBeFalse();
});

test('a checklist only accepts themes that have a view', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('checklists.store'), ['name' => 'X', 'theme' => '../welcome', 'top' => 0, 'left' => 0, 'width' => 400])
        ->assertSessionHasErrors('theme');

    $this->actingAs($user)
        ->post(route('checklists.store'), ['name' => 'X', 'theme' => 'enshrouded', 'top' => 0, 'left' => 0, 'width' => 400])
        ->assertSessionHasNoErrors();
});
