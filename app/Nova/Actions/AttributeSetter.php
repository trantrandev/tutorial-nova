<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class AttributeSetter extends Action
{
    use InteractsWithQueue, Queueable;

    public function __construct(string $label, string $field, $value)
    {
        $this-> name = $label;
        $this->fields = $field;
        $this->value = $value;
    }

    public function handle(ActionFields $fields, Collection $models)
    {
        $ids = $models->pluck('id');
        $modelClass = get_class($models->first());
        $modelClass::whereIn('id', $ids)->update([$this->fields => $this->value]);
    }
    public function fields()
    {
        return [];
    }
}
