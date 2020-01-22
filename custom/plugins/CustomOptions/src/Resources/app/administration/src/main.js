import {Module} from 'src/core/shopware';

import deDE from './snippet/de-DE.json';
import enGB from './snippet/en-GB.json';
import './component/swpa-dependence-component';
import './view/sw-product-detail-custom';
import './page/sw-product-detail';
import './component/sw-order/sw-order-line-items-grid/index';

Module.register('sw-new-tab-custom', {
    snippets: {
        'de-DE': deDE,
        'en-GB': enGB
    },
    routeMiddleware(next, currentRoute) {
        if (currentRoute.name === 'sw.product.detail') {
            currentRoute.children.push({
                name: 'sw.product.detail.custom',
                path: '/sw/product/detail/:id/custom',
                component: 'sw-product-detail-custom',
                meta: {
                    parentPath: 'sw.product.index'
                }
            });
        }
        next(currentRoute);
    }
});
