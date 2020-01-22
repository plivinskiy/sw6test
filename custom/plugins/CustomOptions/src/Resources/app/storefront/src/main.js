import SwpaCustomOptions from './plugin/custom-options/index';

const PluginManager = window.PluginManager;
PluginManager.register('SwpaCustomOptionsAddToCart', SwpaCustomOptions, '[data-add-to-cart]');
