<?php
#App\GP247\Plugins\ShippingStandard\Livewire\AdminLivewire.php

namespace App\GP247\Plugins\ShippingStandard\Livewire;

use GP247\Core\AdminShell\Infrastructure\ConfigForm;
use GP247\Core\Models\AdminConfig;

/**
 * Admin settings screen for the Standard shipping plugin (fee, free-shipping
 * threshold), backed by the admin_config key/value table.
 */
class AdminLivewire extends ConfigForm
{
    protected ?string $permission = null;

    /**
     * Seed default rows for installs made before this settings screen existed,
     * so the form is never empty for an already-installed plugin.
     */
    public function mount(): void
    {
        $defaults = require __DIR__.'/../config.php';
        foreach ($defaults as $key => $value) {
            AdminConfig::firstOrCreate(
                ['group' => $this->group(), 'key' => $key, 'store_id' => $this->storeId()],
                ['code' => $this->group().'_config', 'sort' => 0, 'value' => $value, 'detail' => 'Plugins/ShippingStandard::lang.admin.'.$key]
            );
        }

        parent::mount();
    }

    protected function group(): string
    {
        return 'ShippingStandard';
    }

    protected function heading(): string
    {
        return trans('Plugins/ShippingStandard::lang.title');
    }

    protected function keys(): array
    {
        return ['fee', 'shipping_free'];
    }

    protected function fieldTypes(): array
    {
        return [
            'fee' => 'number',
            'shipping_free' => 'number',
        ];
    }

    /**
     * Both settings are money amounts stored in the shop's base currency, so show
     * the base currency-code hint beside each label (e.g. "(VND)"). Guarded so the
     * screen still renders on installs where the shop currency helper is absent.
     *
     * @return array<string, string>
     */
    protected function fieldHints(): array
    {
        $hint = function_exists('gp247_money_hint') ? gp247_money_hint() : '';

        return [
            'fee' => $hint,
            'shipping_free' => $hint,
        ];
    }
}
