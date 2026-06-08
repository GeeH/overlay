<?php declare(strict_types=1);


namespace Database\Seeders;

use App\Models\Pane;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaneSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('panes')
            ->truncate();;

        $pane = new Pane();
        $pane->id = 1;
        $pane->name = 'test pane';
        $pane->description = 'Test pane for no real reason but to seed db etc etc etc';
        $pane->user_id = 1;
        $pane->text = 'Don\'t forget to follow on Twitch';
        $pane->size = '48px';
        $pane->colour = 'white';
        $pane->bgColour = '#000';
        $pane->top = 100;
        $pane->left = 100;
        $pane->animationIn = 'animate__flipInX';
        $pane->animationOut = 'animate__flipOutX';
        $pane->showFor = 20;
        $pane->extraCss = '';
        $pane->extraClasses = 'p-4 rounded';
        $pane->save();

        $pane = new Pane();
        $pane->id = 2;
        $pane->name = 'TIAS';
        $pane->description = 'Test pane for no real reason but to seed db etc etc etc';
        $pane->user_id = 1;
        $pane->text = 'TIAS';
        $pane->size = '512px';
        $pane->colour = 'green';
        $pane->bgColour = 'transparent';
        $pane->top = 200;
        $pane->width = 1400;
        $pane->left = (1920 - $pane->width) / 2;
        $pane->animationIn = 'animate__zoomIn';
        $pane->animationOut = 'animate__zoomOut';
        $pane->showFor = 20;
        $pane->extraCss = 'margin: auto;';
        $pane->extraClasses = '';
        $pane->save();

        $pane = new Pane();
        $pane->id = 3;
        $pane->name = 'twitter';
        $pane->description = 'Twitter callout';
        $pane->user_id = 1;
        $pane->text = '@GeeH';
        $pane->size = '32px';
        $pane->colour = 'blue';
        $pane->bgColour = 'transparent';
        $pane->top = (1080 - 180 - 20);
        $pane->left = 20;
        $pane->width = 100;
        $pane->height = 180;
        $pane->animationIn = 'animate__rubberBand';
        $pane->showFor = 1;
        $pane->alwaysShown = true;
        $pane->extraCss = '';
        $pane->extraClasses = '';
        $pane->save();
    }
}
