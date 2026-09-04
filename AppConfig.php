<?php
/**
 * Plugin format 1.0
 */
#App\GP247\Plugins\ShippingStandard\AppConfig.php
namespace App\GP247\Plugins\ShippingStandard;

use App\GP247\Plugins\ShippingStandard\Models\ExtensionModel;
use GP247\Core\Models\AdminConfig;
use GP247\Core\Models\AdminHome;
use GP247\Core\Models\AdminMenu;
use GP247\Core\ExtensionConfigDefault;
use Illuminate\Support\Facades\DB;
class AppConfig extends ExtensionConfigDefault
{
    public function __construct()
    {
        //Read config from gp247.json
        $config = file_get_contents(__DIR__.'/gp247.json');
        $config = json_decode($config, true);
    	$this->configGroup = $config['configGroup'];
        $this->configKey = $config['configKey'];
        $this->configCode = $config['configCode'];
        $this->requireCore = $config['requireCore'] ?? [];
        $this->requireComposerPackages = $config['requireComposerPackages'] ?? [];
        $this->requireGp247Extensions = $config['requireGp247Extensions'] ?? [];
        //Path
        $this->appPath = $this->configGroup . '/' . $this->configKey;
        //Language
        $this->title = trans($this->appPath.'::lang.title');
        //Image logo or thumb
        $this->image = $this->appPath.'/'.$config['image'];
        //
        $this->version = $config['version'];
        $this->auth = $config['auth'];
        $this->link = $config['link'];
    }

    public function install()
    {
        $check = AdminConfig::where('key', $this->configKey)
            ->where('group', $this->configGroup)->first();
        if ($check) {
            //Check Plugin key exist
            $return = ['error' => 1, 'msg' =>  gp247_language_render('admin.extension.plugin_exist')];
        } else {
            //Insert plugin to config
            $dataInsert = [
                [
                    'group'  => $this->configGroup,
                    'key'    => $this->configKey,
                    'code'    => $this->configCode,
                    'sort'   => 0,
                    'store_id' => GP247_STORE_ID_GLOBAL,
                    'value'  => self::ON, //Enable extension
                    'detail' => $this->appPath.'::lang.title',
                ],
            ];
            try {
                AdminConfig::insert(
                    $dataInsert
                );

                $defaults = require __DIR__.'/config.php';
                AdminConfig::insert([
                    [
                        'group'    => $this->configKey,
                        'key'      => 'fee',
                        'code'     => $this->configKey.'_config',
                        'sort'     => 1,
                        'store_id' => GP247_STORE_ID_GLOBAL,
                        'value'    => $defaults['fee'] ?? 0,
                        'detail'   => $this->appPath.'::lang.admin.fee',
                    ],
                    [
                        'group'    => $this->configKey,
                        'key'      => 'shipping_free',
                        'code'     => $this->configKey.'_config',
                        'sort'     => 2,
                        'store_id' => GP247_STORE_ID_GLOBAL,
                        'value'    => $defaults['shipping_free'] ?? 0,
                        'detail'   => $this->appPath.'::lang.admin.shipping_free',
                    ],
                ]);

                (new ExtensionModel)->installExtension();


                $checkMenu = AdminMenu::where('key', 'ADMIN_SHOP_SHIPPING')->first();
                if ($checkMenu) { 
                    $position = $checkMenu->id;
                } else {
                    $checkContentMenu = AdminMenu::where('key','ADMIN_SHOP_ORDER')->first();
        
                    $checkMenu = AdminMenu::create([
                        'sort' => 30,
                        'parent_id' => $checkContentMenu->id ?? 0,
                        'title' => 'sHIPPING',
                        'icon' => 'fas fa-mug-hot',
                        'key' => 'ADMIN_SHOP_SHIPPING',
                    ]);
                    $position = $checkMenu->id;
                }

                $shipping = AdminMenu::where('key',$this->configKey)->first();
                if (!$shipping) {
                    //
                }
                        
                $return = ['error' => 0, 'msg' => gp247_language_render('admin.extension.install_success')];
            } catch (\Throwable $e) {
                $return = ['error' => 1, 'msg' => $e->getMessage()];
            }
        }

        return $return;
    }


    public function uninstall()
    {
        //Please delete all values inserted in the installation step
        try {
            (new AdminConfig)
            ->where('key', $this->configKey)
            ->orWhere('code', $this->configKey.'_config')
            ->orWhere('group', $this->configKey)
            ->delete();

            //Admin config home
            AdminHome::where('extension', $this->appPath)->delete();

            //
            AdminMenu::where('key',$this->configKey)
            ->delete();

            (new ExtensionModel)->uninstallExtension();

            $return = ['error' => 0, 'msg' => gp247_language_render('admin.extension.uninstall_success')];
        } catch (\Throwable $e) {
            $return = ['error' => 1, 'msg' => $e->getMessage()];
        }

        return $return;
    }
    
    public function enable()
    {
        $process = (new AdminConfig)
            ->where('group', $this->configGroup)
            ->where('key', $this->configKey)
            ->update(['value' => self::ON]);
        //Admin config home
        AdminHome::where('extension', $this->appPath)->update(['status' => 1]);

        if (!$process) {
            $return = ['error' => 1, 'msg' => gp247_language_render('admin.extension.action_error', ['action' => 'Enable'])];
        }
        $return = ['error' => 0, 'msg' => gp247_language_render('admin.extension.enable_success')];
        return $return;
    }

    public function disable()
    {
        $return = ['error' => 0, 'msg' => ''];
        $process = (new AdminConfig)
            ->where('group', $this->configGroup)
            ->where('key', $this->configKey)
            ->update(['value' => self::OFF]);
        if (!$process) {
            $return = ['error' => 1, 'msg' => 'Error disable'];
        }

        //Admin config home
        AdminHome::where('extension', $this->appPath)->update(['status' => 0]);

        return $return;
    }


    // Remove setup for store

    public function removeStore($storeId = null)
    {
        // code here
    }

    // Setup for store

    public function setupStore($storeId = null)
    {
       // code here
    }


    // Process when click button plugin in admin

    public function clickApp()
    {
        return redirect()->route('admin_shippingstandard.index');
    }

    /**
     * Resolve a money setting for the effective store, in the shop's base currency.
     *
     * Reads the row for the effective store (gp247_plugin_store_id), falling back to
     * the GLOBAL row and finally the plugin's file default — the same two-tier
     * store→GLOBAL inheritance gp247_config uses, but kept group-qualified so the
     * generic keys ("fee"/"shipping_free") never collide with another plugin's.
     *
     * @param string $key Setting key ("fee" | "shipping_free").
     * @return float The amount in base currency.
     *
     * @aidlc-unit plugin-manager
     * @aidlc-story US-shipping-standard-per-store-config
     * @aidlc-adr plugin-manager_per-store-plugin-config
     */
    private function resolveSetting(string $key): float
    {
        $storeId = function_exists('gp247_plugin_store_id')
            ? gp247_plugin_store_id()
            : GP247_STORE_ID_GLOBAL;

        $value = AdminConfig::where('group', $this->configKey)
            ->where('key', $key)
            ->where('store_id', $storeId)
            ->value('value');

        if ($value === null && (string) $storeId !== (string) GP247_STORE_ID_GLOBAL) {
            $value = AdminConfig::where('group', $this->configKey)
                ->where('key', $key)
                ->where('store_id', GP247_STORE_ID_GLOBAL)
                ->value('value');
        }

        return (float) ($value ?? config($this->appPath.'.'.$key) ?? 0);
    }

    /**
     * Get info plugin
     *
     * @return  [type]  [return description]
     */
    public function getInfo()
    {
        $subTotal = 0;
        if (method_exists(\GP247\Shop\Models\ShopCurrency::class, 'sumCartCheckout')) {
            $dataCheckout = \GP247\Shop\Models\ShopCurrency::sumCartCheckout();
            $subTotal = $dataCheckout['subTotal'] ?? 0;
        }

        // Fee and free-shipping threshold are stored in BASE currency (admin enters
        // them in base). Convert both to the DISPLAY currency here so getInfo() honours
        // the plugin money contract: `value` is returned in the display currency and
        // core no longer converts it (ADR storefront_total-method-currency-contract).
        // Compare against $subTotal (already display currency from sumCartCheckout) in
        // the same currency, then return the fee in display currency.
        //
        // Per-store aware (storeScope=store, ADR plugin-manager_per-store-plugin-config):
        // resolve each setting for the EFFECTIVE store (gp247_plugin_store_id — the
        // vendor's store on a marketplace checkout, the domain's store in multi-store),
        // falling back to the GLOBAL row then the file default.
        $fee = gp247_currency_value($this->resolveSetting('fee'));
        $shippingFree = gp247_currency_value($this->resolveSetting('shipping_free'));

        if ($subTotal >= $shippingFree) {
            $fee = 0;
        }
        $arrData = [
            'title' => $this->title,
            'key' => $this->configKey,
            'code' => $this->configCode,
            'image' => $this->image,
            'permission' => self::ALLOW,
            'version' => $this->version,
            'auth' => $this->auth,
            'link' => $this->link,
            'value' => $fee, // this return need for plugin shipping
            'appPath' => $this->appPath
        ];

        return $arrData;
    }
}
