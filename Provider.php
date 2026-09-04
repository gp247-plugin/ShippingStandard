<?php
/**
 * Provides everything needed for the Extension
 */

 $config = file_get_contents(__DIR__.'/gp247.json');
 $config = json_decode($config, true);
 $extensionPath = $config['configGroup'].'/'.$config['configKey'];
 
 $this->loadTranslationsFrom(__DIR__.'/Lang', $extensionPath);
 
 if (gp247_extension_check_active($config['configGroup'], $config['configKey'])) {

     // WHY: declare this plugin's admin config screen as store-scoped so the MultiStore
     // Pro StoreFence lets a store-admin reach the config of their own store (registry
     // idiom, ADR plugin-manager_per-store-plugin-config). No-op without the Pro fence.
     config(['gp247-config.admin.store_scoped_segments' => array_values(array_unique(array_merge(
         (array) config('gp247-config.admin.store_scoped_segments', []),
         ['shippingstandard']
     )))]);

     $this->loadViewsFrom(__DIR__.'/Views', $extensionPath);

     if (file_exists(__DIR__.'/config.php')) {
         $this->mergeConfigFrom(__DIR__.'/config.php', $extensionPath);
     }
 
     if (file_exists(__DIR__.'/function.php')) {
         require_once __DIR__.'/function.php';
     }
 }